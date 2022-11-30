<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        //envoyer un email a notre utilisateur pour lui indiquer l'echec du paiment

        return $this->render('order_cancel/index.html.twig',[
            'order' => $order
        ]);
    }
}
