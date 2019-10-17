<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Entity\Category;
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
    public function show(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post
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
