<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testUserCreation(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    public function testUserProperties(): void
    {
        $this->user->setUsername('ilyes');
        $this->user->setFirstName('mazouzi');
        $this->user->setLastName('IM');
        $this->user->setEmail('ilyes@example.com');
        $this->user->setPassword('password123');
        $this->user->setRoles(['ROLE_USER']);

        $this->assertEquals('johndoe', $this->user->getUsername());
        $this->assertEquals('John', $this->user->getFirstName());
        $this->assertEquals('Doe', $this->user->getLastName());
        $this->assertEquals('john.doe@example.com', $this->user->getEmail());
        $this->assertEquals('password123', $this->user->getPassword());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
    }

    public function testUserRoles(): void
    {
        $this->assertContains('ROLE_USER', $this->user->getRoles());
        
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testUserClients(): void
    {
        $this->assertEmpty($this->user->getClients());
        
        // Test d'ajout de client
        $client = $this->createMock('App\Entity\Client');
        $this->user->addClient($client);
        $this->assertCount(1, $this->user->getClients());
        
        // Test de suppression de client
        $this->user->removeClient($client);
        $this->assertEmpty($this->user->getClients());
    }
} 
