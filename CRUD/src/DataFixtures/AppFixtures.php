<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Facture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur simple
        $user = new User();
        $user->setLastName('eljemli');
        $user->setFirstName('houssam');
        $user->setEmail('houssam@gmail.com');
        $user->setUsername('houssam');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'houssam'));
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $manager->persist($user);

        // Création de quelques clients pour l'admin
        for ($i = 1; $i <= 5; $i++) {
            $client = new Client();
            $client->setNom('Client ' . $i);
            $client->setEmail('client' . $i . '@email.com');
            $client->setTelephone('06 12 34 56 ' . sprintf('%02d', $i));
            $client->setAdresse($i . ' Rue des Exemples');
            $client->setUser($user);
            $manager->persist($client);

            // Création de factures pour chaque client
            for ($j = 1; $j <= 3; $j++) {
                $facture = new Facture();
                $facture->setNumero('FAC-' . $i . '-' . $j);
                $facture->setDate(new \DateTimeImmutable());
                $facture->setMontant(rand(1000, 10000));
                $facture->setEtat(['payee', 'partielle', 'non_payee'][rand(0, 2)]);
                $facture->setNote('Note pour la facture ' . $j);
                $facture->setClient($client);
                $manager->persist($facture);
            }
        }

        $manager->flush();
    }
} 