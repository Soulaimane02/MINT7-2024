<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        //Implementation de mon formulaire d'inscription
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Pour figer les données
            $entityManager->persist($user);
            // Pour enregistrer les données
            $entityManager->flush();
            $this->addFlash(
                'success',
                    'Votre compte à bien été créé ! '

            );
            return $this->redirectToRoute('app_connexion');
        }

        return $this->render('inscription/index.html.twig',[
        'formKey' => $form->createView()
    ]);
    }
}
