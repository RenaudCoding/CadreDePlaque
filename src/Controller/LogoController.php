<?php

namespace App\Controller;

use App\Entity\Logo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LogoController extends AbstractController
{
    // liste des logos
    #[Route('/logo', name: 'app_logo')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $logos = $entityManager->getRepository(Logo::class)->findAll();

        return $this->render('logo/index.html.twig', [
            'logos' => $logos,
        ]);
    }
}
