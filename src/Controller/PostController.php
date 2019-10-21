<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
     * @Route("/blog/{id}", name="post_show", requirements={"id" : "\d+"})
     */
    public function show(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * Création d'un article
     * 
     * @Route("blog/create", name="post_create")
     *
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $post = new Post;

        // $form = $this->createForm(PostType::class, $post);


        $builder = $this->createFormBuilder($post)
            ->add('title', TextType::class, [
                'label' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Donnez un titre percutant à votre article"
                ]
            ])
            ->add('introduction', TextType::class, [
                'label' => "Votre introduction",
                'attr' => [
                    'placeholder' => "Donnez un texte court qui donne envie"
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu",
                'attr' => [
                    'placeholder' => "Contenu de votre article"
                ]
            ])
            ->add('image', UrlType::class, [
                'label' => "URL de l'image",
                'attr' => [
                    'placeholder' => "Adresse web de votre image"
                ]
            ])
            // ->add('category', ChoiceType::class, [
            //     'choices'  => $categoryRepository->findAll(),
            //     'choice_label' => function (Category $category) {
            //         return strtoupper($category->getTitle());
            //     },
            //     'choice_value' => 'id',
            //     'label' => "Catégorie de l'article",
            //     // 'multiple' => true,
            //     'expanded' => true,

            // ]);  OU
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('c')
            //             ->orderBy('c.title', 'ASC');
            //     },
            //     'choice_label' => function (Category $category) {
            //         return strtoupper($category->getTitle());
            //     },
            //     // 'choice_value' => 'id',
            //     'label' => "Catégorie de l'article",
            //     // 'multiple' => true,
            //     'expanded' => true,

            // ]); OU
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                // 'choice_value' => 'id',
                'label' => "Catégorie de l'article",
                // 'multiple' => true,
                // 'expanded' => true,

            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'title',
                'label' => "Etiquettes de l'article",
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'inline'
                ]
            ]);

        $form = $builder->getForm();

        // dd($form->createView());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($post);
            $entityManagerInterface->flush();
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
