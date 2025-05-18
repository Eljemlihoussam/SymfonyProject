<?php

namespace App\Tests\Entity;

use App\Entity\Facture;
use App\Entity\Client;
use PHPUnit\Framework\TestCase;

class FactureTest extends TestCase
{
    private Facture $facture;

    protected function setUp(): void
    {
        $this->facture = new Facture();
    }

    public function testFactureCreation(): void
    {
        $this->assertInstanceOf(Facture::class, $this->facture);
    }

    public function testFactureProperties(): void
    {
        $date = new \DateTime();
        
        $this->facture->setNumero('FACT-2024-001');
        $this->facture->setDateFacturation($date);
        $this->facture->setMontant(1500.00);
        $this->facture->setEtat('Payée');
        $this->facture->setNote('Test de facture');

        $this->assertEquals('FACT-2024-001', $this->facture->getNumero());
        $this->assertEquals($date, $this->facture->getDateFacturation());
        $this->assertEquals(1500.00, $this->facture->getMontant());
        $this->assertEquals('Payée', $this->facture->getEtat());
        $this->assertEquals('Test de facture', $this->facture->getNote());
    }

    public function testFactureClient(): void
    {
        $client = new Client();
        $this->facture->setClient($client);
        $this->assertSame($client, $this->facture->getClient());
    }

    public function testFactureEtatValidation(): void
    {
        // Test état valide
        $this->facture->setEtat('Payée');
        $this->assertEquals('Payée', $this->facture->getEtat());

        $this->facture->setEtat('Partiellement payée');
        $this->assertEquals('Partiellement payée', $this->facture->getEtat());

        $this->facture->setEtat('Non payée');
        $this->assertEquals('Non payée', $this->facture->getEtat());
    }

    public function testFactureMontantValidation(): void
    {
        // Test montant positif
        $this->facture->setMontant(1000.00);
        $this->assertEquals(1000.00, $this->facture->getMontant());

        // Test montant négatif
        $this->expectException(\InvalidArgumentException::class);
        $this->facture->setMontant(-1000.00);
    }
} 