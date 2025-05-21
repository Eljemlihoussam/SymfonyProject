<?php

namespace App\Tests\Entity;

use App\Entity\Facture;
use App\Entity\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

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
        $this->facture->setNumero('FACT-001');
        $this->facture->setDate(new \DateTimeImmutable());
        $this->facture->setMontant(100.50);
        $this->facture->setEtat('Payée');
        $this->facture->setNote('Note de test');
        $this->facture->setDescription('Description de test');

        $this->assertEquals('FACT-001', $this->facture->getNumero());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->facture->getDate());
        $this->assertEquals(100.50, $this->facture->getMontant());
        $this->assertEquals('Payée', $this->facture->getEtat());
        $this->assertEquals('Note de test', $this->facture->getNote());
        $this->assertEquals('Description de test', $this->facture->getDescription());
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
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        // Test montant positif
        $this->facture->setMontant(1000.00);
        $violations = $validator->validate($this->facture);
        $this->assertCount(0, $violations);

        // Test montant négatif
        $this->facture->setMontant(-1000.00);
        $violations = $validator->validate($this->facture);
        $this->assertCount(1, $violations);
        $this->assertEquals('Le montant doit être positif', $violations[0]->getMessage());
    }
} 
