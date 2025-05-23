<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Exemplaire;
use App\Form\CommandeBarretteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    //liste des commandes
    #[Route('/commande', name: 'app_commande')]
    public function listeCommandes(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager->getRepository(Commande::class)->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    // détail d'une commande
    #[Route('/commande/{id}', name: 'show_commande', requirements: ['id' => '\d+'])]
    public function showCommande(Commande $commande): Response
    {

        return $this->render('commande/show.html.twig', [
            'commande' => $commande
        ]);
    }

    //liste des factures
    #[Route('/facture', name: 'app_facture')]
    public function listeFactures(EntityManagerInterface $entityManager): Response
    {
        $factures = $entityManager->getRepository(Facture::class)->findAll();

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);
    }

    //affichage du panier
    #[Route('/panier', name: 'app_panier')]
    public function Panier(EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
            // on récupère la liste des exemplaires de l'utilisateur
            $exemplaires = $this->getUser()->getExemplaires();
            // on récupère les ids de ces exemplaires
            foreach ($exemplaires as $exemplaire) {
                $listeExemplairesId[] = $exemplaire->getId();
            }
            
            // on récupère les articles qui n'ont pas encore été commandés par l'utilisateur
            // en s'assurant que ces articles sont des exemplaires de l'utilisateur   
            $panier = $entityManager->getRepository(Panier::class)->findBy([
                'commande' => null,
                'exemplaire' => $listeExemplairesId] 
                );
        }
        else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('commande/panier.html.twig', [
            'panier' => $panier,
        ]);
    }




    //commander un exemplaire de barrette
    #[Route('/commande/barrette', name: 'commande_exemplaire_barrette')]
    public function commandeBarrette(Request $request, EntityManagerInterface $entityManager): Response
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
    
            // création du formulaire
            $formAddPanier = $this->createForm(CommandeBarretteType::class);
            $formAddPanier->handleRequest($request);

                
            if ($formAddPanier->isSubmitted() && $formAddPanier->isValid()) {

                // on récupère l'id du champ exemplaire
                $exemplaireId = $formAddPanier->get('exemplaire')->getData();
                //on va chercher l'exemplaire correspondant à l'id
                $exemplaireChoisi = $entityManager->getRepository(Exemplaire::class)->find($exemplaireId);

                // on mets les infos saisies dans le formulaire dans le panier (ici uniquement la quantité)
                $panier = $formAddPanier->getData();
                // on rajoute l'id de l'exemplaire choisi dans le panier
                $panier->setExemplaire($exemplaireChoisi);

                // on persiste dans la BDD
                $entityManager->persist($panier);
                $entityManager->flush();

                return $this->redirectToRoute('app_produit');

            }
        }
        else {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('commande/barrette.html.twig', [
            'exemplaires' => $exemplaires,
            'formAddPanier' => $formAddPanier
        ]);

    }
}
