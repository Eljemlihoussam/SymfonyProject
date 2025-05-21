<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // DEBUG: Vérifier si la fonction login est appelée
        error_log("Fonction login appelée!");

        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/login_check', name: 'app_login_check')]
    public function loginCheck(): void
    {
        // Cette méthode ne sera jamais appelée
        // Le pare-feu intercepte la requête avant
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Le contrôleur peut rester vide - il sera intercepté par la configuration de sécurité
        throw new \Exception('Cette méthode ne devrait jamais être appelée directement.');
    }
} 