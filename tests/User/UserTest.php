<?php


namespace App\Tests\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class UserTest extends WebTestCase
{
    public function testAddUser()
    {
        $faker = Faker\Factory::create();
        $user = new User();

        // Value
        $username = $faker->userName;
        $password = $faker->password;
        $email = $faker->email;
        $roles = ["ROLE_USER"];

        // SET
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setRoles($roles);

        // verification
        $this->assertSame($username, $user->getUsername());
        $this->assertSame($password, $user->getPassword());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($roles, $user->getRoles());
    }
}
