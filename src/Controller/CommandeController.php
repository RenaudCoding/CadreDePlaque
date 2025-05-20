<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Form\PanierType;
use App\Entity\Exemplaire;
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

    //commander un exemplaire de barrette
    #[Route('/commande/barrette', name: 'commande_exemplaire_barrette')]
    public function commandeBarrette(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
            $id = $this->getUser()->getId();
            $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy(['user' => $id], ['dateCreation' => 'DESC']);
        
            $formAddPanier = $this->createForm(PanierType::class);
            $formAddPanier->handleRequest($request);

            if ($formAddPanier->isSubmitted() && $formAddPanier->isValid()) {

                $panier = $formAddPanier->getData();
                // $entityManager->persist($panier);
                // $entityManager->flush();

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
