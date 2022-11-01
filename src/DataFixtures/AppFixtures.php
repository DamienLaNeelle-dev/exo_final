<?php

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Users;
use App\Entity\Possessions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {





        $manager->flush();
    }
}
