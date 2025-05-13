<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository, FactureRepository $factureRepository): Response
    {
        $user = $this->getUser();
        $clients = $clientRepository->findBy(['user' => $user]);
        $factures = $factureRepository->createQueryBuilder('f')
            ->join('f.client', 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.date', 'DESC')
            ->getQuery()
            ->getResult();

        $totalClients = count($clients);
        $totalFactures = count($factures);
        $montantTotal = array_reduce($factures, fn($sum, $facture) => $sum + $facture->getMontant(), 0);
        $facturesEnAttente = count(array_filter($factures, fn($facture) => $facture->getEtat() === 'non_payee'));
        $dernieresFactures = array_slice($factures, 0, 6);

        return $this->render('dashboard/index.html.twig', [
            'totalClients' => $totalClients,
            'totalFactures' => $totalFactures,
            'montantTotal' => $montantTotal,
            'facturesEnAttente' => $facturesEnAttente,
            'dernieresFactures' => $dernieresFactures,
        ]);
    }
}
