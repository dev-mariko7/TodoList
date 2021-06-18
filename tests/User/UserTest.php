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

    public function testLogin()
    {
        $client = static::createClient();
        //on arrive sur la page d'accueil
        $crawler = $client->request('GET', '/');

        //on clique sur se connecter
        $link = $crawler->selectLink('Se connecter')->link();
        $crawler = $client->click($link);

        // ce qui doit être visible
        $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Mot de passe")')->count());

        //quand on clique sur
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'username1';
        $form['password'] = 'password1';
        // submit
        $client->submit($form);

        //on affiche le contenu de la reponse
        //on reprend la variable crawler pour récupéré le dernier script
        $crawler = $client->followRedirect();

        // on affiche le script
        //echo $client->getResponse()->getContent();

        //on verifie le dernier script
        $this->assertSame(1, $crawler->filter('html:contains("Vous êtes connecté en tant que username1")')->count());
    }
}
