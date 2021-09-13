<?php


namespace App\Controller\services;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class HandlerUser
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private FlashBagInterface $flashes;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, FlashBagInterface $flashes)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->flashes = $flashes;
    }

    public function isUserCanUpdate($request, User $user, bool $error = false)
    {
        $portUsername = $request->request->get('user')['username'];
        $portRoles = $request->request->get('user')['roles'];
        if($user->getRoles()[0] === "ROLE_ANONYME"){
            if($portRoles !== $user->getRoles()[0]) {
                $error = true;
               $this->flashes->add('danger',"Le role de cette utilisateur ne peut pas être changé");
            }
            if($portUsername !== $user->getUsername()){
                $error = true;
                $this->flashes->add('danger',"L'username de cette utilisateur ne peut pas être changé");
            }
        }
        return $error;
    }
}
