<?php


namespace App\Tests\Task;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class TaskTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

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

    public function testFunctorAddTask()
    {
        // on se connecte
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        /*$this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertTrue($client->getResponse()->isSuccessful());*/
        $link = $crawler->selectLink('Se connecter')->link();
        $crawler = $client->click($link);
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe")')->count());
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'username1';
        $form['password'] = 'password1';
        $client->submit($form);
        //echo $client->getResponse()->getContent();
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Vous êtes connecté en tant que username1")')->count());

        // == ==== on arrive sur la page d'accueil ========
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Créer une nouvelle tâche')->link();
        $crawler = $client->click($link);
        // ce qui doit être visible
        $this->assertSame(1, $crawler->filter('html:contains("Titre")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Description")')->count());

        //quand on clique sur
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre Task 1';
        $form['task[content]'] = 'C\'est la description de la task';

        // submit
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Superbe ! La tâche a été bien été ajoutée.")')->count());
    }

    public function testFunctorTaskToDo()
    {
        // on se connecte
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Se connecter')->link();
        $crawler = $client->click($link);
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe")')->count());
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'username1';
        $form['password'] = 'password1';
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('html:contains("Vous êtes connecté en tant que username1")')->count());

        // == ==== on arrive sur la page d'accueil ========
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Consulter la liste des tâches à faire')->link();
        $crawler = $client->click($link);

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $tasks = $this->entityManager->getRepository(Task::class)->findBy(['isDone' => 0]);
        $nbTasks = count($tasks);
        $this->assertSame($nbTasks, $crawler->filter('span.glyphicon-remove')->count());
        $this->assertSame(0, $crawler->filter('span.glyphicon-ok')->count());
    }

    public function testFunctorTaskIsDone()
    {
        // on se connecte
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Se connecter')->link();
        $crawler = $client->click($link);
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe")')->count());
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'username1';
        $form['password'] = 'password1';
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('html:contains("Vous êtes connecté en tant que username1")')->count());

        // == ==== on arrive sur la page d'accueil ========
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Consulter la liste des tâches terminées')->link();
        $crawler = $client->click($link);

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $tasks = $this->entityManager->getRepository(Task::class)->findBy(['isDone' => 1]);
        $nbTasks = count($tasks);
        $this->assertSame(0, $crawler->filter('span.glyphicon-remove')->count());
        $this->assertSame($nbTasks, $crawler->filter('span.glyphicon-ok')->count());
    }
}
