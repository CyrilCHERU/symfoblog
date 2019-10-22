<?php

namespace App\Form\Transformer;

use App\Repository\CategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryToStringTransformer implements DataTransformerInterface
{
    protected $repository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->repository = $categoryRepository;
    }
    public function transform($value)
    {
        if (!$value) {
            return "";
        }
        return $value->getTitle();
    }

    public function reverseTransform($value) // economie par ex
    { // 1. il faut un repository
        // $this->repository
        // 2. Rechercher la categorie dont title = value
        $category = $this->repository->findOneBy(['title' => $value]);
        // 3. Si on ne la trouve pas, il faut declencher TransformationFailedException()
        if (null === $category) {
            throw new TransformationFailedException("Aucune categorie n'a le titre $value !");
        }
        // 4. Si on l'a trouvé, renvoyer l'entité catégory
        return $category;
    }
}
