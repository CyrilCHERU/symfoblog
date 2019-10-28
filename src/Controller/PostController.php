<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\Category;
use App\Event\CommentAddedEvent;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
    public function show(Post $post, Request $request, EntityManagerInterface $em, EventDispatcherInterface $ed)
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post)
                ->setCreatedAt(new DateTime())
                ->setAuthor($this->getUser());

            // Procedure d'envoi de mail

            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                "Votre commentaire  a bien été ajouté !"
            );

            // pour ce faire on dispatchera plutot un evenement custom qui embarque les donnée du commentaire
            $commentAddedEvent = new CommentAddedEvent($comment);

            $ed->dispatch($commentAddedEvent, 'blog.comment.add');

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
     * @IsGranted("POST_EDIT", subject="post")
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

            $this->addFlash(
                'success',
                "Votre article <strong>{$post->getTitle()}</strong> a bien été modifié !"
            );

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
     * @IsGranted("ROLE_AUTHOR")
     *
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        //$this->denyAccessUnlessGranted("ROLE_ADMIN");
        // $this->getUser()


        $post = new Post;

        $form = $this->createForm(PostType::class, $post);

        // dd($form->createView());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($post);
            $entityManagerInterface->flush();

            $this->addFlash(
                'success',
                "Votre nouvel article <strong>{$post->getTitle()}</strong> a bien été enregistré !"
            );

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
