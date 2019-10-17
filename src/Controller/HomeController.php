<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\Calculator;
use cebe\markdown\Markdown;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        // $manager->flush();

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

    public function list(Request $request, PostRepository $repository)
    {
        dd($request);
        $search = $request->query->get('search', '');

        return $this->render('post/list.html.twig', [
            'posts' => $repository->findByTitle($search)
        ]);
    }

    public function extract(PostRepository $repository, EntityManagerInterface $manager)
    {
        // SELECT p.* FROM post AS p donne
        $articles = $manager->createQuery("
            SELECT p 
            FROM App\Entity\Post AS p")
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getResult();

        dd($articles);

        return $this->render('post/list.html.twig', [
            'posts' => $repository->findAll()
        ]);
    }
}
