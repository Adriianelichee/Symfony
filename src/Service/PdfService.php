<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class PdfService
{
    private $twig;
    private $parameterBag;

    public function __construct(Environment $twig, ParameterBagInterface $parameterBag)
    {
        $this->twig = $twig;
        $this->parameterBag = $parameterBag;
    }

    public function generateQuestionsPdf(array $questions): string
    {
        $html = $this->twig->render('questions/index.html.twig', [
            'questions' => $questions,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}