<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
   private SluggerInterface $slugger;
   public function __construct(SluggerInterface $slugger)
   {
    $this->slugger = $slugger;
   }

    public function load(ObjectManager $manager): void
    { 
        $faker = Factory::create();
        
        for ($i = 0; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->title());
            $movie->setSynopsis($faker->paragraph(10));
            $movie->setCategory($this->getReference('category_' .  $faker->randomElement(CategoryFixtures::CATEGORIES)));
            $movie->setSlug(strtolower($this->slugger->slug($movie->getTitle())));

            $this->addReference('movie_' . $i, $movie);

            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
