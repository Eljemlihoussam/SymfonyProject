<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(Request $request, FactureRepository $factureRepository): Response
    {
        $user = $this->getUser();
        $factures = $factureRepository->createQueryBuilder('f')
            ->join('f.client', 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.date', 'DESC')
            ->getQuery()
            ->getResult();

        $total_amount = array_reduce($factures, function($carry, $facture) {
            return $carry + $facture->getMontant();
        }, 0);

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
            'total_amount' => $total_amount,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facture->setDate(new \DateTimeImmutable());
            $facture->setEtat('non_payee');
            $entityManager->persist($facture);
            $entityManager->flush();

            $this->addFlash('success', 'La facture a été créée avec succès.');
            return $this->redirectToRoute('app_facture_index');
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        if ($facture->getClient()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette facture.');
        }

        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($facture->getClient()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette facture.');
        }

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La facture a été modifiée avec succès.');
            return $this->redirectToRoute('app_facture_index');
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($facture->getClient()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette facture.');
        }

        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
            $this->addFlash('success', 'La facture a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_facture_index');
    }
}
