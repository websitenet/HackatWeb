<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/SignUp/{uc}', name: 'app_créercompte')]
    public function créercompte($uc, ManagerRegistry $doctrine): Response
    {
        if($uc == "formulaire"){
            return $this->render('user/creercompte.html.twig');
        }
        else if ($uc == "validation") {
            $nom=$_POST["nom"];
            $prenom=$_POST["prenom"];
            $email=$_POST["email"];
            $dateNaissance=$_POST["dateNaissance"];
            $tel=$_POST["tel"];
            $lienportfolio=$_POST["portfolio"];
            $mdp=$_POST["mdp"];
            $confmdp=$_POST["confmdp"];
            if($mdp==$confmdp)
            {
                $user = new Utilisateur();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setMail($email);
                $user->setDateNaissance(new \DateTime($dateNaissance));
                $user->setTel($tel);
                $user->setLienPortfolio($lienportfolio);
                $user->setPassword(password_hash($mdp,PASSWORD_BCRYPT));
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->render('home/index.html.twig'); 
            }
            else{
                return $this->render('user/creercompte.html.twig');
            }
        }
    }

    #[Route('', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // obtenir la dernière erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        // obtenir le dernier utilisateur entré
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('home/index.html.twig', ['last_username' =>$lastUsername,'error'=>$error]);
    }
}
