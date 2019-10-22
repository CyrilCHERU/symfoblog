<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller d'affichage des pages
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @return void
     */
    public function home()
    {
        return $this->render("home/index.html.twig");
    }
}
