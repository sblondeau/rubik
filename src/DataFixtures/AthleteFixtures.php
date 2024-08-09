<?php

namespace App\DataFixtures;

use App\Entity\Athlete;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AthleteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $athlete = new Athlete();
            $athlete->setFirstname($faker->firstName());
            $athlete->setLastname($faker->lastName());
            $athlete->setCountry($faker->countryCode());
            $manager->persist($athlete);
        }
        $manager->flush();
    }
}
