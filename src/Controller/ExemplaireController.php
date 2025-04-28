<?php

namespace App\Controller;

use App\Entity\Exemplaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExemplaireController extends AbstractController
{
    // liste des exemplaires
    #[Route('/exemplaire', name: 'app_exemplaire')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $exemplaires = $entityManager->getRepository(Exemplaire::class)->findAll();

        return $this->render('exemplaire/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }
}
