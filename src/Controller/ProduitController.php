<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProduitController extends AbstractController
{
    // liste des produits + formulaire d'ajout d'un produit
    #[Route('/produits', name: 'app_produit')]
    public function listeProduits(Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupération des produits
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        // formulaire d'ajout d'un produit
        $produit = new Produit();
        $formAddProduit = $this->createForm(ProduitType::class, $produit);

        $formAddProduit->handleRequest($request);

        if ($formAddProduit->isSubmitted() && $formAddProduit->isValid()) {

            $produit = $formAddProduit->getData();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'formAddProduit' => $formAddProduit
        ]);
    }


    // détail forfait
    #[Route('/forfait', name: 'app_forfait')]
    public function produitForfait(): Response
    {
        return $this->render('forfait/index.html.twig');
    }


}

