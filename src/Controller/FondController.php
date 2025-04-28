<?php

namespace App\Controller;

use App\Entity\Fond;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FondController extends AbstractController
{
    // liste des fonds
    #[Route('/fond', name: 'app_fond')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $fonds = $entityManager->getRepository(Fond::class)->findAll();

        return $this->render('fond/index.html.twig', [
            'fonds' => $fonds,
        ]);
    }
}
