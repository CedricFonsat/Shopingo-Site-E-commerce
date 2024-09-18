<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
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


    #[Route('/user/promote/{id}', name: 'user_promote')]
    public function promoteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupère le repository de l'entité User
        $userRepository = $entityManager->getRepository(User::class);

        // Cherche l'utilisateur par ID
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Ajoute le rôle ROLE_ADMIN
        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);
            
            // Sauvegarde les modifications dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return new Response('User promoted to ROLE_ADMIN');
    }
}
