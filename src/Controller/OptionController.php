<?php

namespace App\Controller;

use App\Entity\Fond;
use App\Entity\Logo;
use App\Entity\Typo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OptionController extends AbstractController
{
    // liste des options
    #[Route('/options', name: 'app_options')]
    public function listeOptions(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fonds = $entityManager->getRepository(Fond::class)->findAll();
        $typos = $entityManager->getRepository(Typo::class)->findAll();
        $logos = $entityManager->getRepository(Logo::class)->findAll();

        return $this->render('option/index.html.twig', [
            'fonds' => $fonds,
            'typos' => $typos,
            'logos' => $logos,
        ]);
    }





}
