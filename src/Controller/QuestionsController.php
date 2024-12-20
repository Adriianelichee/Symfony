<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Answers;
use App\Service\PdfService;
use App\Service\EmailService;

class QuestionsController extends AbstractController
{
    #[Route('/questions', name: 'app_questions')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $questions = $entityManager->getRepository(Questions::class)->findAll();

        return $this->render('questions/index.html.twig', [
            'questions' => $questions,
        ]);
    }


    #[Route('/api/question/{id}/stats', name: 'api_get_question_stats', methods: ['GET'])]
    public function getQuestionStats(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $question = $entityManager->getRepository(Questions::class)->find($id);
    
        if (!$question) {
            return new JsonResponse(['error' => 'Pregunta no encontrada'], 404);
        }
    
        $answers = $entityManager->getRepository(Answers::class)->findBy(['id_question' => $question]);
    
        $stats = [
            'questionTitle' => $question->getTitle(),
            'answers' => [
                'A' => 0,
                'B' => 0,
                'C' => 0,
                'D' => 0
            ]
        ];
    
        foreach ($answers as $answer) {
            $option = $answer->getChAnswer();
            if (isset($stats['answers'][$option])) {
                $stats['answers'][$option]++;
            }
        }
    
        return new JsonResponse($stats);
    }

    #[Route('/send-questions-pdf', name: 'send_questions_pdf')]
    public function sendQuestionsPdf(EntityManagerInterface $entityManager, PdfService $pdfService, EmailService $emailService): Response
    {
        try {
            $questions = $entityManager->getRepository(Questions::class)->findAll();
            
            // Generar el PDF
            $pdfContent = $pdfService->generateQuestionsPdf($questions);
            
            // Enviar el correo con el PDF adjunto
            $emailService->sendQuestionsPdf($pdfContent);
            
            $this->addFlash('success', 'El PDF con las preguntas ha sido enviado por correo. Verifica MailHog.');
        } catch (\Exception $e) {
            // Log the error
            $this->addFlash('error', 'Hubo un error al enviar el correo: ' . $e->getMessage());
        }
        
        return $this->redirectToRoute('app_questions');
    }
}