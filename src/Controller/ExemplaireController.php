<?php

namespace App\Controller;

use DateTime;
use App\Entity\Base;
use App\Entity\Logo;
use App\Form\BaseType;
use App\Entity\Produit;
use App\Entity\Marquage;
use App\Entity\Decoration;
use App\Entity\Exemplaire;
use App\Form\ExemplaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExemplaireController extends AbstractController
{

    // bibliothèque 
    #[Route('/bibliotheque/user', name: 'user_bibliotheque')]
    public function affichageBibliotheque(EntityManagerInterface $entityManager): Response
    {
        // liste des exemplaires d'un user
        if($this->getUser()) {
            $id = $this->getUser()->getId();
            $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy(['user' => $id], ['dateCreation' => 'DESC']);
            
        }
        else {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('bibliotheque/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }


    // création d'un exemplaire
    #[Route('/create_exemplaire/{id}', name: 'create_exemplaire')]
    public function createExemplaire(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    {
        //formulaire de création d'un exemplaire
        // on créé un nouvel objet exemplaire
        $exemplaire = new Exemplaire;
        // on y ajouter les nouveaux objets utilisés pour personnaliser l'exemplaire
        $exemplaire->addBasis(new Base());
        $exemplaire->addDecoration(new Decoration());
        $exemplaire->addMarquage(new Marquage());

        //on créé le formulaire
        $formCreateExemplaire = $this->createForm(ExemplaireType::class, $exemplaire);
        $formCreateExemplaire->handleRequest($request);

        if ($formCreateExemplaire->isSubmitted() && $formCreateExemplaire->isValid()) {
            
            // dd($formCreateExemplaire);
            // on récupère les données qui compose la création de l'exemplaire
            $exemplaire = $formCreateExemplaire->getData();
            // on rajoute l'ensemble de données nécessaire à référencer l'exemplaire dans la BDD
            // la date actuel
            $exemplaire->setDateCreation(New DateTime("now"));
            // l'id de l'utilisateur
            $exemplaire->setUser($this->getUser());
            // l'id du produit
            $exemplaire->setProduit($produit);
            
            // on enregistre en BDD
            $entityManager->persist($exemplaire);
            $entityManager->flush();

            return $this->redirectToRoute('user_bibliotheque');
        }

        return $this->render('exemplaire/new.html.twig', [
            'form' => $formCreateExemplaire,
        ]);
    }

    // supprimer un exemplaire
    #[Route('bibliotheque/user/delete_exemplaire/{id}', name: 'delete_exemplaire', requirements: ['id' => '\d+'])]
    public function supprimerExemplaire(Exemplaire $exemplaire, EntityManagerInterface $entityManager) {

        if($this->getUser()) {

        $entityManager->remove($exemplaire);
        $entityManager->flush();

        }

        return $this->redirectToRoute('user_bibliotheque');
    }

}