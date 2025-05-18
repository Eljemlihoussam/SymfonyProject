<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/clients')]
#[IsGranted('ROLE_ADMIN')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(Request $request, ClientRepository $clientRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $user = $this->getUser();

        $clients = $clientRepository->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        if ($searchTerm) {
            $clients->andWhere('c.nom LIKE :searchTerm OR c.email LIKE :searchTerm OR c.telephone LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $clients = $clients->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'search_term' => $searchTerm,
        ]);
    }

    #[Route('/search', name: 'app_client_search', methods: ['GET'])]
    public function search(Request $request, ClientRepository $clientRepository): Response
    {
        $searchTerm = $request->query->get('q');
        
        if (!$searchTerm) {
            return $this->redirectToRoute('app_client_index');
        }

        $clients = $clientRepository->search($searchTerm);

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'search_term' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $client->setUser($this->getUser());
        
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Le client a été créé avec succès.');
            return $this->redirectToRoute('app_client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce client.');
        }

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce client.');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le client a été modifié avec succès.');
            return $this->redirectToRoute('app_client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/toggle', name: 'app_client_toggle', methods: ['POST'])]
    public function toggle(Client $client, EntityManagerInterface $entityManager): Response
    {
        $client->setIsActive(!$client->getIsActive());
        $entityManager->flush();

        $this->addFlash('success', sprintf(
            'Le client a été %s avec succès.',
            $client->getIsActive() ? 'activé' : 'désactivé'
        ));

        return $this->redirectToRoute('app_client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce client.');
        }

        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Le client a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
} 