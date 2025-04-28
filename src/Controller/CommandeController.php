<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    //liste des commandes
    #[Route('/commande', name: 'app_commande')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager->getRepository(Commande::class)->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    // détail d'une commande
    #[Route('/commande/{id}', name: 'show_commande')]
    public function show(Commande $commande): Response
    {

        return $this->render('commande/show.html.twig', [
            'commande' => $commande
        ]);
    }

}
