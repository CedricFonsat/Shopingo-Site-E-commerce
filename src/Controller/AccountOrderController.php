<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/compte/mes-commandes', name: 'app_account_order')]
    public function index(): Response
    {

        $orders = $this->em->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/order.html.twig',[
            'orders' => $orders
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'app_account_order_show')]
    public function show($reference): Response
    {

        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        return $this->render('account/order_show.html.twig',[
            'order' => $order
        ]);
    }
}
