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
        $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy(['user' => $id]);
        }
        else {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('exemplaire/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }


    //création d'un exemplaire
    #[Route('/create_exemplaire/{id}', name: 'create_exemplaire')]
    public function createExemplaire(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    {
        //formulaire de création d'un exemplaire
        $exemplaire = new Exemplaire;
        $exemplaire->addBasis(new Base());
        $exemplaire->addDecoration(new Decoration());
        $exemplaire->addMarquage(new Marquage());

        $formCreateExemplaire = $this->createForm(ExemplaireType::class, $exemplaire);

        $formCreateExemplaire->handleRequest($request);

        if ($formCreateExemplaire->isSubmitted() && $formCreateExemplaire->isValid()) {

            $exemplaire = $formCreateExemplaire->getData();
            $exemplaire->setDateCreation(New DateTime("now"));
            $exemplaire->setUser($this->getUser());
            $exemplaire->setProduit($produit);   

            

            $entityManager->persist($exemplaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_exemplaire');
        }

        return $this->render('exemplaire/new.html.twig', [
            'form' => $formCreateExemplaire,


        ]);
    }
}