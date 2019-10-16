<?php

namespace App\Controller;

use App\Service\Calculator;
use cebe\markdown\Markdown;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller d'affichage des pages
 */
class HomeController extends AbstractController
{
    public function index(Calculator $calculator)
    {
        $age = $calculator->calculateAge(1973);

        return $this->render("home/index.html.twig", [
            'name' => 'Cyril',
            'age' => $age
        ]);
    }

    public function plouf(Markdown $parser)
    {
        $text = 'Mon prénom est **Cyril**';

        $html = $parser->parse($text);
        return new Response($html);
    }
}
