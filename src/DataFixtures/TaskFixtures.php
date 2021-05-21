<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TaskFixtures extends Fixture
{
    public const NUMBER_OF_TASK = 50;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= self::NUMBER_OF_TASK; ++$i) {
            $task = new Task();
            $task->setTitle($faker->title);
            $task->setContent($faker->text($maxNbChars = 200));
            $task->setCreatedAt($faker->dateTime($max = 'now'));
            $task->toggle(rand(0, 1));

            $manager->persist($task);
        }
        $manager->flush();
    }
}
