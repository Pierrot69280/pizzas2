<?php

namespace App\DataFixtures;


use App\Entity\Comment;
use App\Entity\Pizza;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i <= 10; $i++) {

            $pizza = new Pizza();
            $pizza->setName($faker->name);
            $pizza->setPrice($faker->randomDigit() + 1);

            $manager->persist($pizza);

        }

        for($j=0;$j<=3;$j++){

            $comment = new Comment();
            $comment->setContent($faker->sentence);
            $comment->setPizza($pizza);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
