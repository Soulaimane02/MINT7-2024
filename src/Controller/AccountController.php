<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // Symfony gère la déconnexion via le firewall
        throw new \Exception("Cette méthode peut être laissée vide - elle sera interceptée par le firewall de déconnexion.");
    }

    #[Route('/compte/modifier-mot-de-passe', name: 'app_edit_pwd')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager->flush();
            $this->addFlash(
                'success',
                    'Votre mot de passe est à jour !'
            );
            
        }

        return $this->render('account/password.html.twig', [
            'modifyPassword' => $form->createView()
        ]);
    }
}
