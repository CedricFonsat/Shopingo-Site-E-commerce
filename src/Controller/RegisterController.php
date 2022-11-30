<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = $form->getData();

            $search_mail = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if (!$search_mail){
                $password =  $hasher->hashPassword($user,$user->getPassword());
                $user->setPassword($password);

                $this->em->persist($user);
                $this->em->flush();

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."<br/>Bienvenue sur le site n°1 en terme de produit destiné au developpeur";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur Shopingo', $content);

                $notification = "Votre inscription c'est coreectement dérouler";
            }else{
                $notification = "L'email que vous avez renseigner existe deja";
            }

        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
