<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Entity\Exemplaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PanierController extends AbstractController
{
    //affichage du panier et validation
    #[Route('/panier', name: 'app_panier')]
    public function affichagePanier(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {

            // on récupère la session
            $session = $request->getSession();

            // si il y a un panier dans la session
            if ($session->get('panier')) {

                // on récupère le panier de la session
                $panierSession = $session->get('panier');

                // pour chaque exemplaire dans le panier renseigné selon l'association exemplaireId => quantite
                foreach($panierSession['exemplaire'] as $exemplaireId => $quantite){

                    // on récupère l'objet exemplaire correspondant à l'id de l'exemplaire
                    $exemplaire = $entityManager->getRepository(Exemplaire::class)->find($exemplaireId);
                    // on créé un nouvel objet panier
                    $exemplairePanier = new Panier();
                    // on met l'objet exemplaire et la quantité récupéré dans l'objet panier
                    $exemplairePanier->setExemplaire($exemplaire);                    
                    $exemplairePanier->setQuantite($quantite);
                    // on stock l'objet panier dans un tableau pour l'injecter dans le formulaire PanierType
                    $panier[] = $exemplairePanier;
                }
            }

            else { 
                throw $this->createAccessDeniedException("Panier vide");
            }


            // Récupération du panier client depuis la BDD
            // on récupère la liste des exemplaires de l'utilisateur
            // $exemplaires = $this->getUser()->getExemplaires();
            // on récupère les ids de ces exemplaires
            // foreach ($exemplaires as $exemplaire) {
            //     $listeExemplairesId[] = $exemplaire->getId();
            // }
            
            // on récupère les articles qui n'ont pas encore été commandés par l'utilisateur (id_commande = null)
            // en s'assurant que ces articles sont des exemplaires de l'utilisateur   
            // $panier = $entityManager->getRepository(Panier::class)->findBy([
            //     'commande' => null,
            //     'exemplaire' => $listeExemplairesId
            //     ]);
            
            
            // création du formulaire avec un tableau associatif
            // la cle "articles" correspond au champs "articles" du formulaire PanierType qui est un CollectionType
            $formQuantitePanier = $this->createForm(PanierType::class, ['articles' => $panier]);
            $formQuantitePanier->handleRequest($request);

            if ($formQuantitePanier->isSubmitted() && $formQuantitePanier->isValid()) {
                
                // on récupère les données du formulaire dans le champ "articles", on obtient un tableau associatif
                $articles = $formQuantitePanier->getData()['articles'];

                
                // pour chaque article dans le tableau associatif
                foreach ($articles as $article) {
                    
                    // on récupère l'id de l'article (exemplaire)
                    $articleId = $article->getExemplaire()->getId();
                    // on récupère la quantité renseignée de l'article
                    $articleQuantite = $article->getQuantite();

                    // à la clé $exemplaireId du tableau on associe la valeur $exemplaireQuantite
                    $panierSession['exemplaire'][$articleId] = $articleQuantite;
                    
                    // on ajoute la paire clé => valeur dans le panier
                    $session->set('panier', $panierSession);

                    // mise en BDD
                    // on persiste
                    // $entityManager->persist($article);

                }

                // mise en BDD
                // seul les quantités qui ont été modifiées seront mise à jour
                // $entityManager->flush();
                dd($panierSession);
            }    
        }
        else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('commande/panier.html.twig', [
            'formQuantitePanier' => $formQuantitePanier->createView(),
            'panier' => $panier,
        ]);
    }

    // supprimer un article du panier
    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer', requirements: ['id' => '\d+'])]
    public function supprimerPanier(Panier $article, EntityManagerInterface $entityManager) {

        if($this->getUser()) {
        
        $entityManager->remove($article);
        $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier');
    }





}
