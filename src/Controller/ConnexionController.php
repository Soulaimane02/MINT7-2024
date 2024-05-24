<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Utiliser Annotation\Route au lieu de Attribute\Route pour Symfony 6.0 ou inférieur
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Gerer les erreurs
        $error = $authenticationUtils->getLastAuthenticationError();

        // Stocker l'email qui a été utilisé pour la dernière tentative de connexion
        $lastMail = $authenticationUtils->getLastUsername();

        return $this->render('connexion/index.html.twig', [
            'error' => $error,
            'last_username' => $lastMail,
        ]);
    }
}
