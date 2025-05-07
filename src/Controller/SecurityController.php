<?php

namespace App\Controller;

use App\Form\ChangeEmailType;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // pour le changement de mail
    #[Route(path: '/changeEmail', name: 'change_email')]
    public function changeEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        // on recupere l'utilisateur connecter , on cree le formulaire
        $user = $this->getUser();
        $formChangeEmail = $this->createForm(ChangeEmailType::class, $user);
        // on envoie le formulaire
        $formChangeEmail->handleRequest($request);
        // si le formulaire est envoye et valide
        if ($formChangeEmail->isSubmitted() && $formChangeEmail->isValid()) {
            // on fait le changement dans user
            $user = $formChangeEmail->getData();
            // et on met a jour la BDD
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre adresse email a été mise à jour avec succès.');
            return $this->redirectToRoute('app_profil');
        }
        return $this->render('security/changeEmail.html.twig', [
            'formChangeEmail' => $formChangeEmail
        ]);
    }

     // pour le changement de mail
     #[Route(path: '/changePassword', name: 'change_password')]
     public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
     {
         // on recupere l'utilisateur connecter , on cree le formulaire
         $user = $this->getUser();
         $formChangePassword = $this->createForm(ChangePasswordType::class, $user);
         // on envoie le formulaire
         $formChangePassword->handleRequest($request);
         // si le formulaire est envoye et valide
         if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {

             // on fait le changement dans user
            $oldPassword = $formChangePassword->get('oldPassword')->getData();
            $newPassword = $formChangePassword->get('newPassword')->getData();
            $confirmPassword = $formChangePassword->get('confirmPassword')->getData();
            

            // isPasswordValid il hashe et verifie que l'empreinte numerique est la meme que celui dans la bdd du user connecter
            if (! ($userPasswordHasher->isPasswordValid($user, $oldPassword)))
            {
                $this->addFlash('success', 'Problème lors du changement de mot de passe.');
                return $this->redirectToRoute('app_profil');
            }
            
            if (! ($newPassword == $confirmPassword)){
                $this->addFlash('success', 'Problème lors du changement de mot de passe.');
                return $this->redirectToRoute('app_profil');
            }

            $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));

             // et on met a jour la BDD
             $entityManager->persist($user);
             $entityManager->flush();
             $this->addFlash('success', 'Votre mot de passe a été mise à jour avec succès.');
             return $this->redirectToRoute('app_profil');
         }
         return $this->render('security/changePassword.html.twig', [
             'formChangePassword' => $formChangePassword
         ]);
     }

}
