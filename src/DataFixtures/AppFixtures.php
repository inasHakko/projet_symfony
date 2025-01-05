<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory; // Utilisez Faker\Factory
use App\Entity\Personne;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Exemple de création d'une entité
        for ($i = 0; $i < 10; $i++) {
            $personne = new Personne();
            $personne->setFirstname($faker->firstName);
            $personne->setName($faker->lastName);
            $personne->setAge($faker->numberBetween(18, 65));
            $personne->setJob($faker->jobTitle);

            $manager->persist($personne);
        }

        $manager->flush();
    }
}
