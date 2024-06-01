<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/account/order', name: 'app_account_order')]
    public function index(): Response
    {
    
        return $this->render('account/order/index.html.twig', [
            'controller_name' => 'AccountOrderController',
        ]);
    }
}
