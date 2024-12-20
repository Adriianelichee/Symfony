<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendQuestionsPdf(string $pdfContent): void
    {
        $email = (new Email())
            ->from('from@example.com')
            ->to('to@example.com')
            ->subject('Lista de Preguntas en PDF')
            ->text('Adjunto encontrarás un PDF con todas las preguntas.')
            ->attach($pdfContent, 'preguntas.pdf', 'application/pdf');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Log the error or throw an exception
            throw new \Exception('Error al enviar el correo: ' . $e->getMessage());
        }
    }
}