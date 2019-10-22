<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tag;
// use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Transformer\CategoryToStringTransformer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    protected $categoryTransformer;
    protected $categoryRepository;

    public function __construct(CategoryToStringTransformer $transformer, CategoryRepository $categoryRepository)
    {
        $this->categoryTransformer = $transformer;
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
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
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'choice_label' => 'title',
            //     // 'choice_value' => 'id',
            //     'label' => "Catégorie de l'article",
            //     // 'multiple' => true,
            //     // 'expanded' => true,

            // ])
            ->add('category', TextType::class, [
                'label' => "Catégorie de l'article",
                'attr' => [
                    'placeholder' => "Donneez une catégorie"
                ],
                'invalid_message' => "Cette catégorie n'existe pas, choisissez entre : " . $this->getCategoriesTitles()
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'title',
                'label' => "Etiquettes de l'article",
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'inline'
                ],
                'by_reference' => false
            ]);

        $builder->get('category')->addModelTransformer($this->categoryTransformer);
        // ou
        // $builder->get('category')->addModelTransformer(
        //     new CallbackTransformer(
        //         function(Category $category){
        //             return $category->getTitle();
        //         },
        //         function (string $title){
        //             // fonction
        //         }
        //     )
        // );
    }

    public function getCategoriesTitles(): string
    {
        // 1. Il faut le repo
        // $this->$categoryRepository
        // 2. Récupérer la liste des categories
        //$categories = $this->categoryRepository->findAll();

        //$titles = []; === à 
        $titles = array_map(function (Category $category) {
            return $category->getTitle();
        }, $this->categoryRepository->findAll());

        // 3. Boucler sur les catégories et faire une phrase
        // foreach ($categories as $category) {
        //     $titles[] = $category->getTitle();
        // }
        // 4. Retourner la phrase
        return implode(', ', $titles);
    }
}
