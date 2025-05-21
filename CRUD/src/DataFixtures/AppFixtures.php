<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs
        $admin = new User();
        $admin->setUsername('houssam');
        $admin->setEmail('houssam@example.com');
        $admin->setFirstName('Houssam');
        $admin->setLastName('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsActive(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'houssam'));
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('symfony');
        $user->setEmail('symfony@example.com');
        $user->setFirstName('Symfony');
        $user->setLastName('User');
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'symfony'));
        $manager->persist($user);

        
        $clients = [
            [
                'nom' => 'Ilyes Mazzouei',
                'nomGerant' => 'Ilyes Mazzouei',
                'raisonSociale' => 'Tech Solutions SARL',
                'email' => 'ilyes.mazzouei@example.com',
                'telephone' => '0612345678',
                'adresse' => '123 Rue de la Paix, Casablanca',
                'ville' => 'Casablanca',
                'pays' => 'Maroc',
                'user' => $admin
            ],
            [
                'nom' => 'Issam Khadiri',
                'nomGerant' => 'Issam Khadiri',
                'raisonSociale' => 'Digital Innovation SA',
                'email' => 'issam.khadiri@example.com',
                'telephone' => '0623456789',
                'adresse' => '456 Avenue Mohammed V, Rabat',
                'ville' => 'Rabat',
                'pays' => 'Maroc',
                'user' => $admin
            ],
            [
                'nom' => 'Ismail Gherram',
                'nomGerant' => 'Ismail Gherram',
                'raisonSociale' => 'Web Services SARL',
                'email' => 'ismail.gherram@example.com',
                'telephone' => '0634567890',
                'adresse' => '789 Boulevard Hassan II, Tanger',
                'ville' => 'Tanger',
                'pays' => 'Maroc',
                'user' => $admin
            ],
            [
                'nom' => 'El Mehdi Lamnaii',
                'nomGerant' => 'El Mehdi Lamnaii',
                'raisonSociale' => 'IT Solutions SA',
                'email' => 'elmehdi.lamnaii@example.com',
                'telephone' => '0645678901',
                'adresse' => '321 Rue Ibn Sina, Fès',
                'ville' => 'Fès',
                'pays' => 'Maroc',
                'user' => $admin
            ],
            [
                'nom' => 'Hamdi Amdi',
                'nomGerant' => 'Hamdi Amdi',
                'raisonSociale' => 'Digital Systems SARL',
                'email' => 'hamdi.amdi@example.com',
                'telephone' => '0656789012',
                'adresse' => '654 Avenue des FAR, Marrakech',
                'ville' => 'Marrakech',
                'pays' => 'Maroc',
                'user' => $admin
            ]
        ];

        foreach ($clients as $clientData) {
            $client = new Client();
            $client->setNom($clientData['nom']);
            $client->setNomGerant($clientData['nomGerant']);
            $client->setRaisonSociale($clientData['raisonSociale']);
            $client->setEmail($clientData['email']);
            $client->setTelephone($clientData['telephone']);
            $client->setAdresse($clientData['adresse']);
            $client->setVille($clientData['ville']);
            $client->setPays($clientData['pays']);
            $client->setIsActive(true);
            $client->setUser($clientData['user']);
            $manager->persist($client);

            // Création de factures pour chaque client
            $services = [
                'Développement Web' => 15000,
                'Maintenance Mensuelle' => 5000,
                'Consultation Technique' => 3000,
                'Formation' => 8000,
                'Support Premium' => 10000
            ];

            // Créer 2-3 factures par client
            $numFactures = rand(2, 3);
            for ($i = 0; $i < $numFactures; $i++) {
                $facture = new Facture();
                $facture->setClient($client);
                $facture->setNumero('FACT-' . date('Y') . '-' . sprintf('%04d', rand(1, 9999)));
                $service = array_rand($services);
                $montant = $services[$service];
                $facture->setMontant($montant);
                $facture->setDescription($service);
                $date = new \DateTimeImmutable();
                $date = $date->modify('-' . rand(0, 180) . ' days');
                $facture->setDate($date);
                $statuts = ['Payée', 'Partiellement payée', 'Non payée'];
                $facture->setEtat($statuts[array_rand($statuts)]);
                if (rand(0, 1)) {
                    $facture->setNote('Note de suivi pour ' . $service);
                }
                $manager->persist($facture);
            }
        }

        $manager->flush();
    }
} 