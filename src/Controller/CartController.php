<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index(Cart $cart, $motif): Response
    {
        if($motif == "annulation")
        {
            $this->addFlash(
                'info',
                    "Paiement annulé : Vous pouvez mettre à jour votre panier et votre commande"
            );
        }
        return $this->render('cart/index.html.twig', ['cart' => $cart->getCart()]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {


        $product = $productRepository->find($id); 
        if ($product) {
            $cart->add($product);
            $this->addFlash('success', "Le produit a été ajouté à votre panier !");
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('error', "Le produit n'a pas été trouvé !");
        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);
        $this->addFlash('success', "Le produit a été supprimé de votre panier !");
        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_home');
    }

}
