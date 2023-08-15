<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface

{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle('movie');
            $movie->setSynopsis('bien bien');
            $movie->setCategory($this->getReference('category_Horreur'));
            $manager->persist($movie);
        }

        $movie = new Movie();
        $movie->setTitle('Arcane');
        $movie->setSynopsis('bien bien');
        $movie->setCategory($this->getReference('category_Horreur'));
        $this->addReference('movie_Arcane', $movie);
        $manager->persist($movie);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
