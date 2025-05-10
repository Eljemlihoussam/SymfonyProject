<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository, InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();
        $clients = $clientRepository->findByUser($user);
        $invoices = $invoiceRepository->findByUser($user);

        $totalClients = count($clients);
        $totalInvoices = count($invoices);
        $totalAmount = array_reduce($invoices, fn($sum, $invoice) => $sum + $invoice->getAmount(), 0);
        $unpaidAmount = array_reduce(
            array_filter($invoices, fn($invoice) => $invoice->getStatus() !== 'PAID'),
            fn($sum, $invoice) => $sum + $invoice->getAmount(),
            0
        );

        return $this->render('dashboard/index.html.twig', [
            'total_clients' => $totalClients,
            'total_invoices' => $totalInvoices,
            'total_amount' => $totalAmount,
            'unpaid_amount' => $unpaidAmount,
            'recent_clients' => array_slice($clients, 0, 5),
            'recent_invoices' => array_slice($invoices, 0, 5),
        ]);
    }
} 