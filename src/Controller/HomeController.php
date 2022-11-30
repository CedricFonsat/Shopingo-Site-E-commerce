<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/', name: 'app_home')]
    public function index()
    {
        $products = $this->em->getRepository(Product::class)->findBy(['isBest' => 1]);

        return $this->render('home/index.html.twig',[
            'products' => $products
        ]);
    }
}
