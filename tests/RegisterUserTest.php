<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        // Il va sur la page inscription
        $crawler = $client->request('GET', '/inscription');
        
        $form = $crawler->selectButton('Valider')->form();
        
        $form['register_user[nom]'] = 'Test';
        $form['register_user[prenom]'] = 'Test';
        $form['register_user[email]'] = 'testtest@exemple.com';
        $form['register_user[plainPassword][first]'] = 'Testtest93@';
        $form['register_user[plainPassword][second]'] = 'Testtest93@';

        $client->followRedirect();
        
    }
    

}
