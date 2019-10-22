<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
use App\Repository\PostRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PostController extends AbstractController
{
    /**
     * @Route("/blog", name="post_index")
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/blog/{category_id}/{id}", name="post_show", requirements={"id" : "\d+"})
     */
    public function show(Post $post, Request $request, EntityManagerInterface $em)
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post)
                ->setCreatedAt(new DateTime());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', [
                'category_id' => $post->getCategory()->getTitle(),
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * Edition pour modification d'un article
     * 
     * @Route("/admin/article/{id}/edit", name="post_edit", requirements={"id": "\d+"})
     *
     * @return void
     */
    public function edit(Post $post, EntityManagerInterface $entityManagerInterface, Request $request)
    {

        $form = $this->createForm(PostType::class, $post);

        // dd($form->createView());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->flush();

            // Generer le route : post_index => /blog
            // $url = $generator->generate('post_index');
            // return new RedirectResponse($url);
            // OU
            return $this->redirectToRoute('post_show', [
                "category_id" => $post->getCategory()->getId(),
                "id" => $post->getId()
            ]);
        }
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Création d'un article
     * 
     * @Route("/admin/article/create", name="post_create")
     *
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $post = new Post;

        $form = $this->createForm(PostType::class, $post);

        // dd($form->createView());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($post);
            $entityManagerInterface->flush();

            // Generer le route : post_index => /blog
            // $url = $generator->generate('post_index');
            // return new RedirectResponse($url);
            // OU
            return $this->redirectToRoute('post_show', [
                "category_id" => $post->getCategory()->getId(),
                "id" => $post->getId()
            ]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/{id}", name="post_category", requirements={"id" : "\d+"})
     */
    public function category(Category $category)
    {
        return $this->render('post/category.html.twig', [
            'category' => $category
        ]);
    }
}
