<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // LEs users
        // 1. creation d'un admin
        $admin = new User;
        $admin->setEmail("admin@admin.com")
            ->setPassword($this->encoder->encodePassword($admin, "password"))
            ->setName('Administrateur')
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        //2. 10 utilisateurs normaux
        $users = [];

        for ($u = 0; $u < 10; $u++) {
            $user = new User;
            $user->setEmail("user$u@gmail.com")
                ->setPassword($this->encoder->encodePassword($user, "password"))
                ->setName($faker->userName);

            $users[] = $user;
            $manager->persist($user);
        }

        $titles = ["Economie", "Santé", "Environnement"];
        $tagTitles = ["Marseille", "Corruption", "Banditisme", "Drogue", "Football", "Embouteillage", "Plages"];

        $tags = [];

        foreach ($tagTitles as $title) {
            $tag = new Tag;
            $tag->setTitle($title);

            $manager->persist($tag);

            $tags[] = $tag;
        }

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

                for ($c = 0; $c < mt_rand(0, 5); $c++) {
                    $comment = new Comment;
                    $comment->setContent($faker->paragraphs(3, true))
                        ->setCreatedAt($faker->dateTimeBetween("-6 months"))
                        ->setPost($post)
                        ->setAuthor($faker->randomElement($users));

                    $manager->persist($comment);
                }


                $postTags = $faker->randomElements($tags, mt_rand(2, 5));

                foreach ($postTags as $tag) {
                    $post->addTag($tag);
                }

                $manager->persist($post);
            }
            $manager->persist($category);
        }

        $manager->flush();
    }
}
