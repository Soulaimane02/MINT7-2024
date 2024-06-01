<?php

namespace App\Controller;



use DateTime;
use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $user = $this->getUser();
        $addresses = $user->getAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')

        ]);

        return $this->render('order/index.html.twig', [
            'delivery_form' => $form->createView(),
        ]);
    }
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart,EntityManagerInterface $entityManager): Response
    {
            $user = $this->getUser();
            $addresses = $user->getAddresses();
            $cartContent = $cart->getCart();  
            $form = $this->createForm(OrderType::class, null, [
                'addresses' => $addresses,
                'action' => $this->generateUrl('app_order_summary')
        ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $address = $form->get('delivery_address')->getData()->getFirstname().' '.$form->get('delivery_address')->getData()->getLastname().'<br>';
                $address .= $form->get('delivery_address')->getData()->getAddress().'<br>';
                $address .= $form->get('delivery_address')->getData()->getPostal().' '.$form->get('delivery_address')->getData()->getCity().'<br>';
                $address .=$form->get('delivery_address')->getData()->getContry().'<br>';
                $address .=$form->get('delivery_address')->getData()->getPhone();
                $order = new Order;
                $order->setUser($this->getUser());
                $order->setCreatedAt(new DateTime());
                $order->setState(1);
                $order->setCarrierName($form->get('carrier')->getData()->getName());
                $order->setCarrierPrice($form->get('carrier')->getData()->getPrice());
                $order->setDelivery($address);
                foreach($cartContent as $product)
                {
                    $orderDetail = new OrderDetail;
                    $orderDetail->setProductName($product['object']->getName());
                    $orderDetail->setProductIllustration($product['object']->getIllustation());
                    $orderDetail->setProductName($product['object']->getName());
                    $orderDetail->setProductPrice($product['object']->getPrice());
                    $orderDetail->setProductTva($product['object']->getTva());
                    $orderDetail->setProductQuantity($product['qty']);
                    $order->addOrderDetail($orderDetail);
                }

                $entityManager->persist($order);
                $entityManager->flush();



                $formData = $form->getData();
                $deliveryAddress = $formData['delivery_address'];
                $carrier = $formData['carrier'];
                return $this->render('order/summary.html.twig', [
                    'delivery_address' => $deliveryAddress,
                    'carrier' => $carrier,
                    'totalWt' => $cart->getTotalWt(),
                    'cart' => $cartContent,
                    'order' => $order

            ]);
        }
        return $this->render('order/summary.html.twig', [
                'form' => $form->createView(),

    ]);
}

    
}
