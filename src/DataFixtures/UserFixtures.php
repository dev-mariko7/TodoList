<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordencoder;
    public const NUMBER_OF_USERS = 10;

    public function __construct(UserPasswordEncoderInterface $passwordencoder)
    {
        $this->passwordencoder = $passwordencoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $roles = ['ROLE_USER'];

        for ($i = 1; $i <= self::NUMBER_OF_USERS; ++$i) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setRoles($roles);

            if (1 === $i) {
                $user->setUsername('username1');
                $user->setPassword($this->passwordencoder->encodePassword($user, 'password1'));
            } else {
                $user->setUsername($faker->userName);
                $user->setPassword($this->passwordencoder->encodePassword($user, 'pass_'.$i));
            }

            $manager->persist($user);
            $this->addReference('User'.$i, $user);
        }

        $manager->flush();
    }
}
