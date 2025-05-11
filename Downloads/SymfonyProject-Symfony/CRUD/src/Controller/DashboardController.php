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
        $totalAmount = array_reduce($factures, fn($sum, $facture) => $sum + $facture->getMontant(), 0);
        $unpaidAmount = array_reduce(
            array_filter($factures, fn($facture) => $facture->getEtat() !== 'Payée'),
            fn($sum, $facture) => $sum + $facture->getMontant(),
            0
        );

        return $this->render('dashboard/index.html.twig', [
            'total_clients' => $totalClients,
            'total_invoices' => $totalFactures,
            'total_amount' => $totalAmount,
            'unpaid_amount' => $unpaidAmount,
            'recent_clients' => array_slice($clients, 0, 5),
            'recent_invoices' => array_slice($factures, 0, 5),
        ]);
    }
}
