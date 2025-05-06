<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    // liste des utilisateurs
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    // Pour la page de profil
    #[Route('/profil', name:'app_profil')]
    public function profil (): Response{
        // je récupère l'utilisateur en session
        $user = $this->getUser();

        return $this->render('user/profil.html.twig', ['user' => $user]);
    }
}
