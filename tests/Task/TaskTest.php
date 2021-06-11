<?php


namespace App\Tests\Task;

use App\Entity\Task;
use App\Entity\User;
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

        //UserEntity for User_id field
        $user = new User();
        $user->setEmail($faker->email);
        $user->setUsername($faker->userName);
        $user->setPassword('password1');
        $user->setRoles(['ROLE_USER']);

        // SET
        $task = new Task();
        $task->toggle($isDone);
        $task->setCreatedAt($created);
        $task->setLastModification($created);
        $task->setTitle($title);
        $task->setContent($content);
        $task->setUser($user);

        // VERIFICATION
        $this->assertSame($isDone, $task->isDone());
        $this->assertSame($created, $task->getCreatedAt());
        $this->assertSame($created, $task->getLastModification());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($content, $task->getContent());
        $this->assertSame($user, $task->getUser());

    }
}
