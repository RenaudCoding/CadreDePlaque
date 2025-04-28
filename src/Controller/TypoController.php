<?php

namespace App\Controller;

use App\Entity\Typo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TypoController extends AbstractController
{
    // liste des typos
    #[Route('/typo', name: 'app_typo')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $typos = $entityManager->getRepository(Typo::class)->findAll();

        return $this->render('typo/index.html.twig', [
            'typos' => $typos,
        ]);
    }
}
