<?php

namespace App\Controller;

use DateTime;
use App\Entity\Base;
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
    #[Route('/bibliotheque', name: 'user_bibliotheque')]
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
            // l'url de l'image par defaut d'un exemplaire selon le produit
            switch ($produit->getId()) {
                case 1:
                    $exemplaire->setUrlExemplaire('img/exemplaires/barette_default.jpg');
                    break;
                case 2:
                    $exemplaire->setUrlExemplaire('img/exemplaires/cache_avant_default.jpg');
                    break;
                case 3:
                $exemplaire->setUrlExemplaire('img/exemplaires/cache_arriere_default.jpg');
                break;
            }
            
            // on enregistre en BDD
            // $entityManager->persist($exemplaire);
            // $entityManager->flush();

            return $this->redirectToRoute('user_bibliotheque');
        }

        return $this->render('exemplaire/new.html.twig', [
            'form' => $formCreateExemplaire,
            'produit' => $produit
        ]);
    }

    // supprimer un exemplaire
    #[Route('bibliotheque/delete_exemplaire/{id}', name: 'delete_exemplaire', requirements: ['id' => '\d+'])]
    public function supprimerExemplaire(Exemplaire $exemplaire, EntityManagerInterface $entityManager, Request $request) {

        if($this->getUser()) {

            // on récupère l'id de l'exemplaire
            $id = $exemplaire->getId();
            
            // on récupère la session
            $session = $request->getSession();

            // si il n'y a pas de panier dans la session on créé une tableau associatif, on l'initialise
            if (!$session->get('panier')) {
                //on y créé un tableau associatif exemplaire => quantité
                $session->set('panier', [
                    'exemplaire'=> []]);
            }
            // on récupère le panier en session
            $panierSession = $session->get('panier');
          
            // pour chaque exemplaire présent dans le panier
            foreach($panierSession['exemplaire'] as $exemplaireId => $quantite) {
                // si l'id de l'exemplaire à supprimer correspond à l'id d'un exemplaire dans le panier
                if($id == $exemplaireId) {
                    // message flash et refresh page
                    $this->addFlash(
                    'alert',
                    'Cet exemplaire est dans votre panier, vous ne pouvez pas le supprimer');
                    return $this->redirectToRoute('user_bibliotheque');
                }
            }

            // on supprime l'exemplaire
            $entityManager->remove($exemplaire);
            $entityManager->flush();

        }

        return $this->redirectToRoute('user_bibliotheque');
    }

}