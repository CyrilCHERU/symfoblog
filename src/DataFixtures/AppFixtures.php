<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $titles = ["Economie", "Politique", "Sports", "Santé", "Environnement"];

        foreach ($titles as $title) {
            $category = new Category;
            $category->setTitle($title);

            $limit = mt_rand(5, 20);

            for ($i = 0; $i < $limit; $i++) {
                $post = new Post;

                $post->setTitle($faker->catchPhrase)
                    ->setIntroduction($faker->sentence(15))
                    ->setContent($faker->paragraphs(5, true))
                    ->setImage($faker->imageUrl(400, 400))
                    ->setCategory($category);

                $manager->persist($post);
            }
            $manager->persist($category);
        }

        $manager->flush();
    }
}
