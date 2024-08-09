<?php

namespace App\DataFixtures;

use App\Entity\Ranking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RankingFixtures extends Fixture
{
    public const COUNTRIES = ['us', 'au', 'fr', 'nz', 'hu', 'gb', 'ca', 'cn', 'jp', 'ma', 'sa', 'it', 'es', 'be'];
    public function load(ObjectManager $manager): void
    {
        foreach(self::COUNTRIES as $country) {
            $ranking = new Ranking();
            $ranking->setCountry($country);
            $ranking->setGold(rand(0,30));
            $ranking->setSilver(rand(0,30));
            $ranking->setBronze(rand(0,30));
            $manager->persist($ranking);
        }
        $manager->flush();
    }
}
