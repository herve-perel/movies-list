<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $episode = new Episode();
        $episode->setTitle('Welcome to the Playground');
        $episode->setNumber(1);
        $episode->setSynopsis('bien bien cool vraiment');
        $episode->setSeason($this->getReference('season1_Arcane'));
        $manager->persist($episode);

        $episode = new Episode();
        $episode->setTitle('Welcome to the ');
        $episode->setNumber(2);
        $episode->setSynopsis('bien bien cool vraiment');
        $episode->setSeason($this->getReference('season1_Arcane'));
        $manager->persist($episode);

        $episode = new Episode();
        $episode->setTitle('Welcome');
        $episode->setNumber(3);
        $episode->setSynopsis('bien bien cool vraiment');
        $episode->setSeason($this->getReference('season1_Arcane'));
        $manager->persist($episode);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
