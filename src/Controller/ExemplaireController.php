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
    // liste des exemplaires
    #[Route('/exemplaires', name: 'app_exemplaire')]
    public function listExemplaires(EntityManagerInterface $entityManager): Response
    {
        $exemplaires = $entityManager->getRepository(Exemplaire::class)->findAll();

        return $this->render('exemplaire/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }

    // liste des exemplaires d'un user
    #[Route('/exemplaires/user', name: 'user_exemplaire')]
    public function listExemplairesUser(EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
            $id = $this->getUser()->getId();
            $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy(['user' => $id], ['dateCreation' => 'DESC']);
        }
        else {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('exemplaire/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }


    // liste des exemplaires de barrettes d'un user
    #[Route('/exemplaires/user/barrette', name: 'user_exemplaire_barrette')]
    public function listExemplairesBarretteUser(EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
            $id = $this->getUser()->getId();
            // on récupère le produit 'barrette'
            $produit = $entityManager->getRepository(Produit::class)->findOneBy(
                ['nomProduit' => 'barrette']);
            // on récupère les exemplaires de barrette de l'utilisateur    
            $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy([
                'user' => $id, 
                'produit' => $produit], 
                ['dateCreation' => 'DESC']);
        }
        else {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('exemplaire/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }

private function getClickedForm($bouton) {

            }

    //création d'un exemplaire
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
            // l'ensemble de données nécessaire

            
            // on enregistre en BDD
            // $entityManager->persist($exemplaire);
            // $entityManager->flush();

            return $this->redirectToRoute('app_exemplaire');
        }

        return $this->render('exemplaire/new.html.twig', [
            'form' => $formCreateExemplaire,


        ]);
    }
}