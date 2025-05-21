<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository, FactureRepository $factureRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();
        
        // Récupération des clients de l'utilisateur
        $clients = $clientRepository->findBy(['user' => $user]);
        
        // Récupération des factures des clients de l'utilisateur
        $factures = $factureRepository->createQueryBuilder('f')
            ->join('f.client', 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        
        // Filtrage des factures par état
        $factures_payees = array_filter($factures, fn($f) => $f->getEtat() === 'payee');
        $factures_en_attente = array_filter($factures, fn($f) => $f->getEtat() === 'non_payee');
        $factures_partiellement_payees = array_filter($factures, fn($f) => $f->getEtat() === 'partielle');
        
        // Calcul du montant total
        $montant_total = array_reduce($factures, fn($carry, $f) => $carry + $f->getMontant(), 0);
        
        // Préparation des données pour le graphique d'évolution
        $dates = [];
        $montants = [];
        $factures_par_mois = [];
        
        foreach ($factures as $facture) {
            $mois = $facture->getDate()->format('Y-m');
            if (!isset($factures_par_mois[$mois])) {
                $factures_par_mois[$mois] = 0;
            }
            $factures_par_mois[$mois] += $facture->getMontant();
        }
        
        ksort($factures_par_mois);
        
        foreach ($factures_par_mois as $mois => $montant) {
            $dates[] = $mois;
            $montants[] = $montant;
        }
        
        return $this->render('dashboard/index.html.twig', [
            'clients' => $clients,
            'factures_payees' => $factures_payees,
            'factures_en_attente' => $factures_en_attente,
            'factures_partiellement_payees' => $factures_partiellement_payees,
            'montant_total' => $montant_total,
            'dates' => json_encode($dates),
            'montants' => json_encode($montants),
        ]);
    }
}
