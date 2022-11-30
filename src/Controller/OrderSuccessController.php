<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index(Cart $cart,$stripeSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }


        if (!$order->isIsPaid()){
            //vider la session cart
            $cart->remove();

            //modifier le statut isPaid de notre commande en mettant 1
            $order->setIsPaid(1);
            $this->em->flush();
            //Envoyer un email a notre client pour lui confirmer sa commande

            $mail = new Mail();
            $content = "Bonjour ".$order->getUser()->getFirstname()."<br/>Nous vous remercion pour la commande que vous avez passée sur notre site.";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Shopingo est bien validée.', $content);
        }
        //Afficher les quelques information de commande de l'utilisateur

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
