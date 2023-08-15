<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //src/DataFixtures/SeasonFixtures.php
        $season = new Season();
        $season->setNumber(1);
        $season->setMovie($this->getReference('movie_Arcane'));
        $season->setSynopsis('bien bien cool vraiment');
        $season->setTitle('season1');
        $this->addReference('season1_Arcane', $season);
        $manager->persist($season);

        $season = new Season();
        $season->setNumber(2);
        $season->setMovie($this->getReference('movie_Arcane'));
        $season->setSynopsis('bien bien cool vraiment');
        $season->setTitle('season2');
        $this->addReference('season2_Arcane', $season);
        $manager->persist($season);

        $season = new Season();
        $season->setNumber(3);
        $season->setMovie($this->getReference('movie_Arcane'));
        $season->setSynopsis('bien bien cool vraiment');
        $season->setTitle('season3');
        $this->addReference('season3_Arcane', $season);
        $manager->persist($season);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MovieFixtures::class,
        ];
    }
}
