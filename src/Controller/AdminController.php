<?php

namespace App\Controller;

use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Answers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{

    #[Route('/admin', name: 'admin_index')]
    public function adminIndex(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Obtener todas las preguntas
        $questions = $entityManager->getRepository(Questions::class)->findAll();

        return $this->render('questions/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/admin/create-question', name: 'show_create_question_form', methods: ['GET'])]
    public function showCreateQuestionForm(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
    }


    #[Route('/admin/create-question', name: 'create_question', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Verificar si el usuario tiene rol de administrador
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Obtener los datos del formulario
        $title = $request->request->get('title');
        $startDate = new \DateTime($request->request->get('start_date'));
        $endDate = new \DateTime($request->request->get('end_date'));
        $questionA = $request->request->get('question_a');
        $questionB = $request->request->get('question_b');
        $questionC = $request->request->get('question_c');
        $questionD = $request->request->get('question_d');
        $correctAnswer = $request->request->get('correctAnswer');

        // Crear una nueva instancia de Questions
        $question = new Questions();
        $question->setTitle($title);
        $question->setStartDate($startDate);
        $question->setEndDate($endDate);
        $question->setQuestionA($questionA);
        $question->setQuestionB($questionB);
        $question->setQuestionC($questionC);
        $question->setQuestionD($questionD);
        $question->setCorrectAnswer($correctAnswer);

        // Persistir la nueva pregunta en la base de datos
        $entityManager->persist($question);
        $entityManager->flush();

        // Redirigir al usuario a la página de administración con un mensaje de éxito
        $this->addFlash('success', 'La pregunta ha sido creada exitosamente.');
        return $this->redirectToRoute('admin_index');
    }

    #[Route('/admin/edit-question/{id}', name: 'edit_question')]
    public function editQuestion(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $question = $entityManager->getRepository(Questions::class)->find($id);

        if (!$question) {
            throw $this->createNotFoundException('La pregunta no existe');
        }

        if ($request->isMethod('POST')) {
            // Actualizar la pregunta con los datos del formulario
            $question->setTitle($request->request->get('title'));
            $question->setStartDate(new \DateTime($request->request->get('start_date')));
            $question->setEndDate(new \DateTime($request->request->get('end_date')));
            $question->setQuestionA($request->request->get('question_a'));
            $question->setQuestionB($request->request->get('question_b'));
            $question->setQuestionC($request->request->get('question_c'));
            $question->setQuestionD($request->request->get('question_d'));
            $question->setCorrectAnswer($request->request->get('correctAnswer'));

            $entityManager->flush();

            $this->addFlash('success', 'La pregunta ha sido actualizada exitosamente.');
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/edit.html.twig', [
            'question' => $question,
        ]);
    }




#[Route('/admin/delete-question/{id}', name: 'delete_question', methods: ['POST'])]
public function deleteQuestion(Request $request, EntityManagerInterface $entityManager, int $id): RedirectResponse
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $question = $entityManager->getRepository(Questions::class)->find($id);

    if (!$question) {
        $this->addFlash('error', 'La pregunta no existe.');
        return $this->redirectToRoute('admin_index');
    }

    // Verificar el token CSRF
    if (!$this->isCsrfTokenValid('delete-question-'.$id, $request->request->get('_token'))) {
        $this->addFlash('error', 'Token CSRF inválido.');
        return $this->redirectToRoute('admin_index');
    }

    // Eliminar las respuestas asociadas a la pregunta
    $answers = $entityManager->getRepository(Answers::class)->findBy(['id_question' => $question]);
    foreach ($answers as $answer) {
        $entityManager->remove($answer);
    }

    // Eliminar la pregunta
    $entityManager->remove($question);
    $entityManager->flush();

    $this->addFlash('success', 'La pregunta ha sido eliminada exitosamente.');
    return $this->redirectToRoute('admin_index');
}

}