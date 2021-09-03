<?php

namespace App\Controller\services;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AnonymeUser
{
    //test
    public function update(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $tasks = $entityManager->getRepository(Task::class)->findBy(['user'=> null]);

        foreach ($tasks as $task)
        {
            if($task->getUser() === null)
            {
                $updateTask = $entityManager->getRepository(Task::class)->find($task->getId());
                $user = new User();
                $anonymeUser = $entityManager->getRepository(User::class)->findBy(['username'=>'Anonyme']);
                if(!$anonymeUser) {
                    $this->create($user, $passwordEncoder, $entityManager);
                    $anonymeUser = $entityManager->getRepository(User::class)->findBy(['username'=>'Anonyme']);
                }
                //dump($anonymeUser);exit;
                $updateTask->setUser($anonymeUser[0]);
                $entityManager->flush();
            }
        }
    }

    public function create(User $user, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $user->setUsername('Anonyme');
        $user->setPassword($passwordEncoder->encodePassword($user, 'password1'));
        $user->setRoles(['USER_ROLE']);
        $user->setEmail('anonyme@anonyme.ano');

        $entityManager->persist($user);
        $entityManager->flush();
    }
}
