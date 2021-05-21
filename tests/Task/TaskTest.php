<?php


namespace App\Tests\Task;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class TaskTest extends WebTestCase
{
    public function testAddTask()
    {
        $faker = Faker\Factory::create();

        // Values
        $isDone = rand(0,1);
        $created = $faker->dateTime($max = 'now');
        $title = $faker->title;
        $content = $faker->text($maxChars = 200);

        // SET
        $task = new Task();
        $task->toggle($isDone);
        $task->setCreatedAt($created);
        $task->setTitle($title);
        $task->setContent($content);

        // VERIFICATION
        $this->assertSame($isDone, $task->isDone());
        $this->assertSame($created, $task->getCreatedAt());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($content, $task->getContent());

    }
}
