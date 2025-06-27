<?php

namespace App\Controller;

use DateTime;
use App\Entity\Tarif;
use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\LivraisonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    // détail d'une commande
    #[Route('/commande/{id}', name: 'show_commande', requirements: ['id' => '\d+'])]
    public function showCommande(Commande $commande): Response
    {

        return $this->render('commande/show.html.twig', [
            'commande' => $commande
        ]);
    }

    // liste des factures
    #[Route('/facture', name: 'app_facture')]
    public function listeFactures(EntityManagerInterface $entityManager): Response
    {
        $factures = $entityManager->getRepository(Facture::class)->findAll();

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);
    }


    // grille de tarif de tous les produits en JSON
    #[Route('/get-all-tarifs', name: 'get_all_tarifs', methods: ['GET'])]
    public function getAllTarifs(EntityManagerInterface $entityManager): JsonResponse
    {
        //tableau vide pour stocker la grille de tarif
        $result = [];
        // on récupère tous les produits
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        // pour chaque produit
        foreach ($produits as $produit) {
            // on récupère tous les tarifs en utilisant une méthode personnalisée 
            // la méthode classe les tarifs suivant le seuil de quantité dans l'ordre décroissant
            // la fonction JS getPrixUnitaire() récupèrera le tarif correspondant au seuil le plus élevé atteint par la quantité
            // if (quantite >= tarif.seuil)
            $tarifs = $entityManager->getRepository(Tarif::class)->findTarifsByProduit($produit);
            // on transforme les objets Tarif récupérés en tableaux pour le JSON en récupérant uniquement les champs nécessaires
            $result[$produit->getId()] = array_map(fn($tarif) => [
                'seuil' => $tarif->getSeuilQuantite(),
                'prix' => $tarif->getPrixUnitaire(),
            ], $tarifs);
        }
        
        // on renvoi au format JSON
        return new JsonResponse(['tarifs' => $result]);
    }

    #[Route('/commande/livraison', name: 'livraison')]
    public function adresseLivraison(EntityManagerInterface $entityManager, Request $request): Response{
        // TODO: récap des articles commandés

        //on créé le formulaire
        $formLivraison = $this->createForm(LivraisonType::class);
        $formLivraison->handleRequest($request);

        if ($formLivraison->isSubmitted() && $formLivraison->isValid()) {
            
            // on récupère les données qui compose la création de l'exemplaire
            $commande = $formLivraison->getData();
            // dd($commande);
            // on récupère la session
            $session = $request->getSession();

            // si il n'y a pas de panier dans la session on créé une tableau associatif, on l'initialise
            if (!$session->get('livraison')) {
                //on y créé un tableau associatif exemplaire => quantité
                $session->set('livraison', []);
                
                // syntaxe si on veux rajouter des clés => valeurs
                // $session->set('panier', [
                //     'exemplaire_id'=> $exemplaireId,
                //     'quantite' => $exemplaireQuantite]);
            }

            // on récupère le tableau associatif de l'adresse de livraison en session
            $livraison = $session->get('livraison');
            // on rempli le tableau avec les données de livraison
            $livraison['nom'] = $commande->getNom();
            $livraison['prenom'] = $commande->getPrenom();
            $livraison['adresse'] = $commande->getAdresse();
            $livraison['cp'] = $commande->getCp();
            $livraison['ville'] = $commande->getVille();

            // on remts le tableau rempli dans la session
            $session->set('livraison', $livraison);

            dd($session);

            // à la clé $exemplaireId du tableau on associe la valeur $exemplaireQuantite
            // $panierSession['exemplaire'][$exemplaireId] = $exemplaireQuantite;
            
            // on ajoute la paire clé => valeur dans le panier
            // $session->set('panier', $panierSession);

        

        }

        return $this->render('commande/livraison.html.twig', [
            'formLivraison' => $formLivraison,
        ]);
        }
    }

