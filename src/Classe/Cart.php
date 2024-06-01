<?php

namespace App\Classe;


use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
        
    }

    public function add($product)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []); 

        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()]['qty'] += 1;
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }

        $session->set('cart', $cart);
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart', []);
    }
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
    public function decrease($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart');

        if (isset($cart[$id])) {
          if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty'] -= 1;
            } else {
                unset($cart[$id]);
            }
            $session->set('cart', $cart);
        }
    }
    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;
    
        if ($cart !== null) {
            foreach ($cart as $product) {
                $quantity += $product['qty'];
            }
        }
    
        return $quantity;
    }
    public function getTotalWt(): float
{
    $cart = $this->getCart();
    $totalWt = 0;

    foreach ($cart as $item) {
        $product = $item['object'];
        $qty = $item['qty'];
        $tva = $product->getTva();
        $priceWithTva = $product->getPrice() * (1 + $tva / 100);
        $totalWt += $priceWithTva * $qty;
    }

    return round($totalWt, 2);
}

    

}
