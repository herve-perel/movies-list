<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
   public function __construct(SluggerInterface $slugger)
   {
    $this->slugger = $slugger;
   }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 200; $i++) {
            $episode = new Episode();
            $episode->setNumber($faker->numberBetween(1, 10));
            $episode->setTitle($faker->name());
            $episode->setSynopsis($faker->paragraphs(3, true));
            $episode->setSeason($this->getReference('season_' . $i % 20));
            $episode->setDuration($faker->numberBetween(10, 60));
            $episode->setSlug(strtolower($this->slugger->slug($episode->getTitle())));


            $manager->persist($episode);
        }

        $manager->flush();

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
