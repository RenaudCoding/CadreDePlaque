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

            // s'il ni a pas de panier en session on en créé un
            if (!$session->get('panier')) {
                //on y créé un tableau associatif exemplaire => quantité
                $session->set('panier', [
                    'exemplaire'=> []]);
            }

            // on récupère le panier de la session
            $panierSession = $session->get('panier');
            
            // si il y a des exemplaires dans le panier
            if(count($panierSession['exemplaire']) > 0) {
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
            // si il n'y a pas d'exemplaire dans le panier
            else { 
                // le panier est vide
                $panier= [];
            }
            
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

                    return $this->redirectToRoute('livraison');

                }
            }    

            // on retourne le panier avec le formulaire des quantités
            return $this->render('panier/index.html.twig', [
            'formQuantitePanier' => $formQuantitePanier->createView(),
            'panier' => $panier,
            ]);
            
        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    // supprimer un article du panier
    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer', requirements: ['id' => '\d+'])]
    public function supprimerPanier(Exemplaire $exemplaire, EntityManagerInterface $entityManager, Request $request) {

        if($this->getUser()) {
        
            // si l'exemplaire existe et qu'il appartient à l'utilisateur
            if($exemplaire && $exemplaire->getUser() == $this->getUser()){
                // on récupère l'id de l'exemplaire
                $exemplaireId = $exemplaire->getId();
                
                // on récupère la session
                $session = $request->getSession();

                // on récupère le panier en session
                $panierSession = $session->get('panier');

                // on retire l'exemplaire du panier
                unset($panierSession['exemplaire'][$exemplaireId]);

                // on remet le panier en session
                $session->set('panier', $panierSession);
            
            }
            else { 
                throw $this->createAccessDeniedException("Exemplaire inaccessible");
            }
        }

        return $this->redirectToRoute('app_panier');
    }





}
