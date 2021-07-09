<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUMBER_OF_TASK = 50;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= self::NUMBER_OF_TASK; ++$i) {

            $user_ref_rand = rand(1,UserFixtures::NUMBER_OF_USERS);
            $currentUser = $this->getReference("User".$user_ref_rand);

            $task = new Task();
            $task->setTitle($faker->title);
            $task->setContent($faker->text($maxNbChars = 200));
            $task->setCreatedAt($faker->dateTime($max = 'now'));
            $task->setLastModification($faker->dateTime($max = 'now'));
            $task->toggle(rand(0, 1));
            $task->setUser($currentUser);

            $manager->persist($task);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
