<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testClientCreation(): void
    {
        $this->assertInstanceOf(Client::class, $this->client);
    }

    public function testClientProperties(): void
    {
        $this->client->setNomGerant('Doe');
        $this->client->setPrenomGerant('John');
        $this->client->setRaisonSociale('Entreprise Test');
        $this->client->setTelephone('0612345678');
        $this->client->setAdresse('123 Rue Test');
        $this->client->setVille('Paris');
        $this->client->setPays('France');

        $this->assertEquals('Doe', $this->client->getNomGerant());
        $this->assertEquals('John', $this->client->getPrenomGerant());
        $this->assertEquals('Entreprise Test', $this->client->getRaisonSociale());
        $this->assertEquals('0612345678', $this->client->getTelephone());
        $this->assertEquals('123 Rue Test', $this->client->getAdresse());
        $this->assertEquals('Paris', $this->client->getVille());
        $this->assertEquals('France', $this->client->getPays());
    }

    public function testClientUser(): void
    {
        $user = new User();
        $this->client->setUser($user);
        $this->assertSame($user, $this->client->getUser());
    }

    public function testClientFactures(): void
    {
        $this->assertEmpty($this->client->getFactures());
        
        // Test d'ajout de facture
        $facture = $this->createMock('App\Entity\Facture');
        $this->client->addFacture($facture);
        $this->assertCount(1, $this->client->getFactures());
        
        // Test de suppression de facture
        $this->client->removeFacture($facture);
        $this->assertEmpty($this->client->getFactures());
    }
} 