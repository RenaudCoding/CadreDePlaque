<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

Class PanierService 
{
    //ajout d'un produit dans le panier
    public function AddToCart(Request $resquest, EntityManagerInterface $entityManager) {
        
        // on créé la session
        $session = $request->getSession();

        // si il n'y a pas de panier dans la sessionn on créé une tableau associatif, on l'initialise
        if (!$session->get('panier')) {
            $session->set('panier', [
                'exemplaire' => [],
                'quantite' => []
            ]);
        }
        // on récupère le panier en session
        $panierSession = $session->get('panier');

        $panierSession['exemplaire'] = $article->getExemplaire();
        $panierSession['quantite'] = $article->getQuantite();

        
        return $panierSession;




            
            

        }


}