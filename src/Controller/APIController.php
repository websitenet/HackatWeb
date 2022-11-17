<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Hackathon;
use App\Entity\Evenement;
use App\Entity\Conference;
use App\Entity\Initiation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/*use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;*/


class APIController extends AbstractController
{


    //api retournant un tableau de tout les hackathons
    #[Route('/api/hackathon', name: 'app_apiLesHackathons')]
    public function apiLesHackathons(ManagerRegistry $doctrine): JsonResponse
    {
        //On recupère tout les hackathons
        
        $repository = $doctrine->getRepository(Hackathon::class);
        $lesHackathons = $repository->findAll();

        //On créé et remplit un tableau avec tout les hackathons
        $tableauHackathons=[];

        //Boucle pour récupérer les informations de chaque hackathon
        foreach($lesHackathons as $leHackathon){
            $tableauHackathons[] = [
                    'id'=>$leHackathon->getId(),
                    'dateDebut'=>$leHackathon->getDateDebut(),
                    'lieu'=>$leHackathon->getLieu(),
                    'rue'=>$leHackathon->getRue(),
                    'ville'=>$leHackathon->getVille(),
                    'codePostal'=>$leHackathon->getCodePostal(),
                    'theme'=>$leHackathon->getTheme(),
                    'description'=>$leHackathon->getDescription(),
                    'image'=>$leHackathon->getImage(),
                    'nbPlaces'=>$leHackathon->getNbPlaces(),
                    'heureDebut'=>$leHackathon->getHeureDebut(),
                    'dateFin'=>$leHackathon->getDateFin(),
                    'heureFin'=>$leHackathon->getHeureFin(),
                    'dateLimite'=>$leHackathon->getDateLimite()
            ];
            }   
        //Renvoit du tableau des hackathons en JS
        return new JsonResponse($tableauHackathons);
    }



    //api d'un seul hacathon via l'id
    #[Route('/api/hackathon/{id}', name: 'app_apiUnHackathon')]
    public function apiUnHackathon($id,  ManagerRegistry $doctrine): Response
    {
        //On recherche un hackathon via l'id de l'url parmis tout les hackathons
        $repository = $doctrine->getRepository(Hackathon::class);
        $leHackathon = $repository->findOneBy(['id' => $id]);

        // On créé et remplit un tableau pour le hackathon
        $tableauHackathon=[];
        $tableauHackathon[]=[
            'id'=>$leHackathon->getId(),
            'dateDebut'=>$leHackathon->getDateDebut(),
            'lieu'=>$leHackathon->getLieu(),
            'rue'=>$leHackathon->getRue(),
            'ville'=>$leHackathon->getVille(),
            'codePostal'=>$leHackathon->getCodePostal(),
            'theme'=>$leHackathon->getTheme(),
            'description'=>$leHackathon->getDescription(),
            'image'=>$leHackathon->getImage(),
            'nbPlaces'=>$leHackathon->getNbPlaces(),
            'heureDebut'=>$leHackathon->getHeureDebut(),
            'dateFin'=>$leHackathon->getDateFin(),
            'heureFin'=>$leHackathon->getHeureFin(),
            'dateLimite'=>$leHackathon->getDateLimite()
        ];

        // retour du tableau en reponse JS
        return new JsonResponse($tableauHackathon);
    }

    //route de test
    #[Route('/api/testHeritage', name: 'app_apiUnHackathon')]
    public function apiTestHeritage( Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $hackaton = $doctrine->getRepository(Hackathon::class)->findOneBy(['id' => 1]);
        $evenement = new Initiation();
        $evenement->setLibelle("libelle test");
        $evenement->setDate(new \DateTime(date("d-m-y")));
        $evenement->setDuree(2);
        $evenement->setHeure(new \DateTime(date('h:i:s')));
        $evenement->setSalle("test");
        $evenement->setIdHackathon($hackaton);
        $evenement->setType("Initiation");
        $evenement->setNbPlaceLimite(23);
        $entityManager->persist($evenement);
        $entityManager->flush();
    }
}
