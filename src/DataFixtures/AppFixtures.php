<?php

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Users;
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


        for ($i = 0; $i <= 10; $i++) {
            $user = new Users();
            $user->setPrenom($this->faker->firstname('male', 'female'))
                ->setNom($this->faker->lastname())
                ->setEmail($this->faker->email())
                ->setAdresse($this->faker->streetName())
                ->setTel($this->faker->phoneNumber());
            $manager->persist($user);
        }


        $manager->flush();
    }
}
