<?php

namespace App\Classe;

//ensigne maniere d'utiliser $session
//use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly RequestStack $requestStack){}

    public function add($id): void
    {
        $session = $this->requestStack->getSession();

        $cart = $session->get('cart', []);

        if (!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
    }

    public function get()
    {
        $session = $this->requestStack->getSession();
        return $session->get('cart');
    }

    public function remove()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('cart');
    }

    public function delete($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        unset($cart[$id]);

      return $session->set('cart', $cart);
    }
    public function decrease($id)
    {
        $session = $this->requestStack->getSession();

        $cart = $session->get('cart', []);

        if ($cart[$id] > 1){
            //retirer quantiter
            $cart[$id]--;
        }else{
            //supprimer mon produit
            unset($cart[$id]);
        }

        return $session->set('cart', $cart);
    }

    public function getFull(): array
    {
        $cartComplete = [];

        if ($this->get()){
            foreach ($this->get() as $id => $quantity){
                $product_object = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);
                if (!$product_object){
                    $this->delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }


}