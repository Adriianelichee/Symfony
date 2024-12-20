<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Questions;
use App\Entity\Answers;
use Doctrine\ORM\EntityManagerInterface;

class AnswersController extends AbstractController
{
    #[Route('/answers/{id}', name: 'app_answers')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $question = $entityManager->getRepository(Questions::class)->find($id);

        if (!$question) {
            throw $this->createNotFoundException('No se encontró la pregunta con id '.$id);
        }

        // Verificar si la pregunta ha expirado
        if ($question->getEndDate() < new \DateTime()) {
            $this->addFlash('error', 'Esta pregunta ha expirado y ya no se pueden enviar respuestas.');
            return $this->redirectToRoute('app_questions'); // Asumiendo que tienes una ruta 'app_questions' para listar todas las preguntas
        }

        return $this->render('answers/index.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/submit-answer', name: 'submit_answer', methods: ['POST'])]
    public function submitAnswer(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Verificar si el usuario está autenticado y no es un admin
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Los administradores no pueden responder preguntas.');
        }

        $questionId = $request->request->get('questionId');
        $answerChoice = $request->request->get('ch_answer');

        $question = $entityManager->getRepository(Questions::class)->find($questionId);

        if (!$question) {
            throw $this->createNotFoundException('No se encontró la pregunta con id '.$questionId);
        }

        // Verificar si la pregunta aún no ha expirado
        if ($question->getEndDate() < new \DateTime()) {
            throw $this->createAccessDeniedException('Esta pregunta ha expirado y ya no se pueden enviar respuestas.');
        }

        $answer = new Answers();
        $answer->setIdQuestion($question);
        $answer->setChAnswer($answerChoice);
        $answer->setTimestamp(new \DateTime());

        $user = $this->getUser();
        $answer->setIdUser($user);

        $entityManager->persist($answer);
        $entityManager->flush();

        $this->addFlash('success', 'Tu respuesta ha sido guardada.');

        return $this->redirectToRoute('app_questions');
    }
}