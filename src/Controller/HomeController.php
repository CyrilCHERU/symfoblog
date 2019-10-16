<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\Calculator;
use cebe\markdown\Markdown;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller d'affichage des pages
 */
class HomeController extends AbstractController
{
    public function index(Calculator $calculator, ObjectManager $manager)
    {
        $post = new Post;
        $post->setTitle("Article de test")
            ->setIntroduction("Ceci est une petite introduction de mon article")
            ->setContent("Voici mon article. J'avoue il est bidon")
            ->setImage("http://placehold.it/400x400/0000FF/808080");


        $manager->persist($post);
        $manager->flush();

        die("plouf");
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

    public function list(PostRepository $repository)
    {
        $posts = $repository->findAll();
        dd($posts);
    }
}
