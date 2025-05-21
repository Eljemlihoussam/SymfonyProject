<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Client;

class ClientControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public function testIndexRequiresLogin(): void
    {
        $this->client->request('GET', '/client/');
        $this->assertResponseRedirects('/login');
    }

    public function testNewClient(): void
    {
        // Créer un utilisateur de test
        $user = new User();
        $user->setUsername('testuser');
        $user->setPassword('password');
        $user->setEmail('test@example.com');
        $user->setLastName('Test');
        $user->setFirstName('User');
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Simuler la connexion
        $this->client->loginUser($user);

        // Tester la création d'un nouveau client
        $crawler = $this->client->request('GET', '/client/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form();
        $form['client[nomGerant]'] = 'Doe';
        $form['client[raisonSociale]'] = 'Test Company';
        $form['client[telephone]'] = '0612345678';
        $form['client[adresse]'] = '123 Test Street';
        $form['client[ville]'] = 'Test City';
        $form['client[pays]'] = 'Test Country';

        $this->client->submit($form);
        $this->assertResponseRedirects('/client/');
    }

    public function testEditClient(): void
    {
        // Créer un utilisateur de test
        $user = new User();
        $user->setUsername('testuser2');
        $user->setPassword('password');
        $user->setEmail('test2@example.com');
        $user->setLastName('Test');
        $user->setFirstName('User');
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Créer un client de test
        $client = new Client();
        $client->setNomGerant('Doe');
        $client->setRaisonSociale('Test Company');
        $client->setTelephone('0612345678');
        $client->setAdresse('123 Test Street');
        $client->setVille('Test City');
        $client->setPays('Test Country');
        $client->setUser($user);
        
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        // Simuler la connexion
        $this->client->loginUser($user);

        // Tester l'édition du client
        $crawler = $this->client->request('GET', '/client/' . $client->getId() . '/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Mettre à jour')->form();
        $form['client[raisonSociale]'] = 'Updated Company';

        $this->client->submit($form);
        $this->assertResponseRedirects('/client/');
    }

    public function testDeleteClient(): void
    {
        // Créer un utilisateur de test
        $user = new User();
        $user->setUsername('testuser3');
        $user->setPassword('password');
        $user->setEmail('test3@example.com');
        $user->setLastName('Test');
        $user->setFirstName('User');
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Créer un client de test
        $client = new Client();
        $client->setNomGerant('Doe');
        $client->setRaisonSociale('Test Company');
        $client->setTelephone('0612345678');
        $client->setAdresse('123 Test Street');
        $client->setVille('Test City');
        $client->setPays('Test Country');
        $client->setUser($user);
        
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        // Simuler la connexion
        $this->client->loginUser($user);

        // Tester la suppression du client
        $this->client->request('DELETE', '/client/' . $client->getId());
        $this->assertResponseRedirects('/client/');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
} 