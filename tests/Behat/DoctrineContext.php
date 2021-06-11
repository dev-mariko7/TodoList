<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class DoctrineContext implements Context
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordencoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordencoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordencoder = $passwordencoder;
    }

    /**
     * @BeforceScenario
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function initDatabase()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $this->loadFixtures();
    }

    public function loadFixtures()
    {
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);
        $loader = new Loader();

        //user
        $userDataFixtures = new UserFixtures($this->passwordencoder);
        $loader->addFixture($userDataFixtures);
        //task
        $taskDataFixtures = new TaskFixtures();
        $loader->addFixture($taskDataFixtures);

        $executor->execute($loader->getFixtures());
    }

}
