<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AddressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    #[Route('/compte/addresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/addresses.html.twig');


    }
    #[Route('/compte/addresse/ajouter/{id}', name: 'app_account_address_form', defaults: [
        'id' => null
    ])]
    public function form(AddressRepository $addressRepository, Request $request, $id = null): Response
    {
        if(isset($id))
        {
            $address = $addressRepository->find($id);
            if(!$address || $address->getUser() != $this->getUser())
            {
                return $this->redirectToRoute('app_account_addresses');

            }

        }
        else{
            $address = new Address;
            $address->setUser($this->getUser());
        }
        $form= $this->createForm(AddressUserType::class,$address);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                    "Votre adresse à bien été enregistrée !"
            );

            return $this->redirectToRoute('app_account_addresses');

        }
        return $this->render('account/form.html.twig',[
            'addressForm' => $form
        ]);
    }
    #[Route('/compte/adresse/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id,AddressRepository $addressRepository): Response
    {
        $address= $addressRepository->find($id);
        if(!$address || $address->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('app_account_addresses');
        }
        $this->addFlash(
            'success',
                'Votre adresse à été supprimée !'
        );
        $this->entityManager->remove($address);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_account_address_form');
       
    }

    
}