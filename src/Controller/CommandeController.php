<?php

namespace App\Controller;

use App\Entity\Tarif;
use App\Entity\Panier;
use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\PanierType;
use App\Entity\Exemplaire;
use App\Form\CommandeBarretteType;
use App\Form\CommandeCacheplaqueType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    // détail d'une commande
    #[Route('/commande/{id}', name: 'show_commande', requirements: ['id' => '\d+'])]
    public function showCommande(Commande $commande): Response
    {

        return $this->render('commande/show.html.twig', [
            'commande' => $commande
        ]);
    }

    // liste des factures
    #[Route('/facture', name: 'app_facture')]
    public function listeFactures(EntityManagerInterface $entityManager): Response
    {
        $factures = $entityManager->getRepository(Facture::class)->findAll();

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);
    }

    
    // ajouter des cadres de plaque dans le panier
    #[Route('/commande/cadre', name: 'commande_cadre')]
    public function commandeCadre(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {

            // on récupère l'id de l'utilisateur
            $id = $this->getUser()->getId();
            // on récupère le produit 'barrette'
            $produits = $entityManager->getRepository(Produit::class)->findBy(
                ['nomProduit' => ["cadre de plaque d'immatriculation avant", 
                                    "cadre de plaque d'immatriculation arrière"]]);
          
            // création du formulaire
            // $formAddPanier = $this->createForm(CommandeCadreType::class);
            // $formAddPanier->handleRequest($request);

            // validation du formulaire
            // if ($formAddPanier->isSubmitted() && $formAddPanier->isValid()) {
                
        }
        
        return $this->render('commande/cadre.html.twig', [
            'produits' => $produits
        ]);
    }


    // ajouter un exemplaire de barrette dans le panier
    #[Route('/commande/barrette', name: 'commande_exemplaire_barrette')]
    public function ajoutpPanierBarrette(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {

            // on récupère l'id de l'utilisateur
            $id = $this->getUser()->getId();
            // on récupère le produit 'barrette'
            // TODO: rajouter une condition au cas ou le produit n'existe pas !
            $produit = $entityManager->getRepository(Produit::class)->findOneBy(
                ['nomProduit' => 'barrette']);
            // on récupère les exemplaires de barrette de l'utilisateur    
            $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy([
                'user' => $id, 
                'produit' =>  $produit], 
                ['dateCreation' => 'DESC']);
                
            // création du formulaire
            $formAddPanier = $this->createForm(CommandeBarretteType::class);
            $formAddPanier->handleRequest($request);

            // validation du formulaire
            if ($formAddPanier->isSubmitted() && $formAddPanier->isValid()) {
                
                // on récupère l'id du champ exemplaire
                $exemplaireId = $formAddPanier->get('exemplaire')->getData();
                //on va chercher l'exemplaire (entité) correspondant à l'id
                $exemplaireChoisi = $entityManager->getRepository(Exemplaire::class)->find($exemplaireId);
                // on récupère la quantité
                $exemplaireQuantite = $formAddPanier->get('quantite')->getData();

                // en cas de manip malveillante sur l'id de l'exemplaire on vérifie:
                if($exemplaireChoisi && // que l'exemplaire existe
                    $exemplaireChoisi->getProduit() == $produit && // que l'exemplaire est bien une barrette
                    $exemplaireChoisi->getUser() == $this->getUser()) // que l'exemplaire appartient à l'utilisateur
                    {
                        // si la quantité est valide
                        if ($exemplaireQuantite > 0) {
                            
                            // on récupère la session
                            $session = $request->getSession();

                            // si il n'y a pas de panier dans la session on créé une tableau associatif, on l'initialise
                            if (!$session->get('panier')) {
                                //on y créé un tableau associatif exemplaire => quantité
                                $session->set('panier', [
                                    'exemplaire'=> []]);

                                // syntaxe si on veux rajouter des clés => valeurs
                                // $session->set('panier', [
                                //     'exemplaire_id'=> $exemplaireId,
                                //     'quantite' => $exemplaireQuantite]);
                            }
                            // on récupère le panier en session
                            $panierSession = $session->get('panier');

                            // à la clé $exemplaireId du tableau on associe la valeur $exemplaireQuantite
                            $panierSession['exemplaire'][$exemplaireId] = $exemplaireQuantite;
                            
                            // on ajoute la paire clé => valeur dans le panier
                            $session->set('panier', $panierSession);

                            // supprimer le panier de la session
                            // $session->remove('panier');                         
                            // dd($session->get('panier'));
                            

                            //
                            // Enregistrement en BDD
                            //
                            /*
                            $panier = new Panier;
                            // on met l'exemplaire (entité) choisi dans le panier 
                            $panier->setExemplaire($exemplaireChoisi);
                            // on met la quantité renseignée dans le panier
                            $panier->setQuantite($exemplaireQuantite);
                            
                            // on persiste dans la BDD
                            $entityManager->persist($panier);
                            $entityManager->flush();
                            */
                        }
                        // si la quantité n'est pas renseignée
                        else { 
                            throw $this->createAccessDeniedException("Quantité  non renseignée");
                        }
       
                    return $this->redirectToRoute('app_produit');
                }
                // exemplaire non valide
                else {
                    throw $this->createAccessDeniedException("Problème de saisie");
                }
            }
        }
        // si l'utilisateur n'est pas connecté
        else {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('commande/barrette.html.twig', [
            'exemplaires' => $exemplaires,
            'formAddPanier' => $formAddPanier
        ]);
    }


    // ajouter des exemplaires de cache plaque dans le panier
    #[Route('/commande/cacheplaque', name: 'commande_exemplaire_cacheplaque')]
    public function commandeCacheplaque(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {

            // affichage des caches plaque avant et arrière de l'utilisateur
            // on récupère l'id de l'utilisateur
            $id = $this->getUser()->getId();           
            // on récupère les produits 'cache plaque avant' et 'cache plaque arrière'
            $produit = $entityManager->getRepository(Produit::class)->findBy(
                ['nomProduit' => ['cache plaque avant', 'cache plaque arrière']]);
            // on récupère les exemplaires de cache plaque de l'utilisateur    
            $exemplaires = $entityManager->getRepository(Exemplaire::class)->findBy([
                'user' => $id, 
                'produit' => $produit], 
                ['dateCreation' => 'DESC']);
    
            // création du formulaire
            $formAddPanier = $this->createForm(CommandeCacheplaqueType::class);
            $formAddPanier->handleRequest($request);

            // si le formulaire et soumis et validé
            if ($formAddPanier->isSubmitted() && $formAddPanier->isValid()) {
                
                // on récupère l'id des champs exemplaire avant et exemplaire arrière
                $exemplaireAvantId = $formAddPanier->get('exemplaireAvant')->getData();
                $exemplaireArriereId = $formAddPanier->get('exemplaireArriere')->getData();

                //
                // Contrôle et validation des exemplaires entrés dans le formulaire
                //

                // si on a une id dans le champ exemplaire avant
                if($exemplaireAvantId) {
                    // on va chercher l'exemplaire correspondant à l'id
                    $exemplaireAvantChoisi = $entityManager->getRepository(Exemplaire::class)->find($exemplaireAvantId);
                    // on récupère le produit 'cache plaque avant'
                    $produitAvant = $entityManager->getRepository(Produit::class)->findOneBy(
                        ['nomProduit' => 'cache plaque avant']);
                    
                    // en cas de manip malveillante sur l'id de l'exemplaire on vérifie:
                    if($exemplaireAvantChoisi && // que l'exemplaire existe
                        $exemplaireAvantChoisi->getProduit() == $produitAvant && // que l'exemplaire est bien un cache plaque avant
                        $exemplaireAvantChoisi->getUser() == $this->getUser()) // que l'exemplaire appartient à l'utilisateur
                        {
                        // on valide l'entrée de la partie "avant" du fomulaire pour la suite du traitement
                        $validationAvant = 1;
                    }
                    // exemplaire non valide
                    else {
                        throw $this->createAccessDeniedException("AVANT PAS VALIDE");
                    }
                }
                // si il n'y a pas d'id dans le champ exemplaire avant, pas de traitement par la suite
                else { 
                    $validationAvant = 0;
                }

                // si on a une id dans le champ exemplaire arrière
                if($exemplaireArriereId) {
                    //on va chercher l'exemplaire correspondant à l'id
                    $exemplaireArriereChoisi = $entityManager->getRepository(Exemplaire::class)->find($exemplaireArriereId);
                    // on récupère le produit 'cache plaque arrière'
                    $produitArriere = $entityManager->getRepository(Produit::class)->findOneBy(
                        ['nomProduit' => 'cache plaque arrière']);

                    // en cas de manip malveillante sur l'id de l'exemplaire on vérifie:
                    if($exemplaireArriereChoisi && // que l'exemplaire existe
                        $exemplaireArriereChoisi->getProduit() == $produitArriere && // que l'exemplaire est bien un cache plaque arrière
                        $exemplaireArriereChoisi->getUser() == $this->getUser()) // que l'exemplaire appartient à l'utilisateur
                        {
                        // on valide l'entrée de la partie "arrière" du fomulaire pour la suite du traitement
                        $validationArriere = 1;
                    }
                    // exemplaire non valide
                    else {
                        throw $this->createAccessDeniedException("ARRIERE PAS VALIDE");
                    }
                }
                // si il n'y a pas d'id dans le champ exemplaire arrière, pas de traitement par la suite
                else { 
                    $validationArriere = 0;
                }

                //
                // Traitement du formulaire après validation
                //
                // on traite la partie "avant" du formulaire validée précedemment
                if($validationAvant) {
                    // on récupère la quantité de l'exemplaire avant
                    $exemplaireAvantQuantite = $formAddPanier->get('quantiteAvant')->getData();
                    // si la quantité est valide
                    if ($exemplaireAvantQuantite > 0) {

                        // on récupère la session
                            $session = $request->getSession();

                            // si il n'y a pas de panier dans la session on créé une tableau associatif, on l'initialise
                            if (!$session->get('panier')) {
                                //on y créé un tableau associatif exemplaire => quantité
                                $session->set('panier', [
                                    'exemplaire'=> []]);

                                // syntaxe si on veux rajouter des clés => valeurs
                                // $session->set('panier', [
                                //     'exemplaire_id'=> $exemplaireId,
                                //     'quantite' => $exemplaireQuantite]);
                            }
                            // on récupère le panier en session
                            $panierSession = $session->get('panier');

                            // à la clé $exemplaireId du tableau on associe la valeur $exemplaireQuantite
                            $panierSession['exemplaire'][$exemplaireAvantId] = $exemplaireAvantQuantite;
                            
                            // on ajoute la paire clé => valeur dans le panier
                            $session->set('panier', $panierSession);

                            // supprimer le panier de la session
                            // $session->remove('panier');                         
                            // dd($session->get('panier'));

                        //
                        // mise en BDD
                        // $panierAvant = new Panier;
                        // on mets les infos récupérées dans le formulaire dans le panier
                        // $panierAvant->setExemplaire($exemplaireAvantChoisi);
                        // $panierAvant->setQuantite($exemplaireAvantQuantite);

                        // on persiste dans la BDD
                        // $entityManager->persist($panierAvant);
                        // $entityManager->flush();
                    }
                    // si la quantité n'est pas renseignée
                    else { 
                        throw $this->createAccessDeniedException("Quantité avant non renseignée");
                    }
                }
                
                // on traite la partie "arrière" du formulaire validée précedemment
                if($validationArriere) {
                    //on récupère la quantité de l'exemplaire arrière
                    $exemplaireArriereQuantite = $formAddPanier->get('quantiteArriere')->getData();
                    // si la quantité est valide
                    if($exemplaireArriereQuantite > 0) {

                        // on récupère la session
                        $session = $request->getSession();

                        // si il n'y a pas de panier dans la session on créé une tableau associatif, on l'initialise
                        if (!$session->get('panier')) {
                            //on y créé un tableau associatif exemplaire => quantité
                            $session->set('panier', [
                                'exemplaire'=> []]);

                            // syntaxe si on veux rajouter des clés => valeurs
                            // $session->set('panier', [
                            //     'exemplaire_id'=> $exemplaireId,
                            //     'quantite' => $exemplaireQuantite]);
                        }
                        // on récupère le panier en session
                        $panierSession = $session->get('panier');

                        // à la clé $exemplaireId du tableau on associe la valeur $exemplaireQuantite
                        $panierSession['exemplaire'][$exemplaireArriereId] = $exemplaireArriereQuantite;
                        
                        // on ajoute la paire clé => valeur dans le panier
                        $session->set('panier', $panierSession);

                        // supprimer le panier de la session
                        // $session->remove('panier');                         
                        // dd($session->get('panier'));

                        //
                        //mise en BDD
                        // $panierArriere = new Panier;
                        // on mets les infos récupérées dans le formulaire dans le panier
                        // $panierArriere->setExemplaire($exemplaireArriereChoisi);
                        // $panierArriere->setQuantite($exemplaireArriereQuantite);

                        // on persiste dans la BDD
                        // $entityManager->persist($panierArriere);
                        // $entityManager->flush();
                        }
                    // si la quantité n'est pas renseignée
                    else { 
                        throw $this->createAccessDeniedException("Quantité arrière non renseignée");
                    }
                }

                return $this->redirectToRoute('app_produit');

            }
        }
        // si l'utilisateur n'est pas connecté
        else {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('commande/cacheplaque.html.twig', [
            'exemplaires' => $exemplaires,
            'formAddPanier' => $formAddPanier
        ]);

    }

    

    // grille de tarif de tous les produits en JSON
    #[Route('/get-all-tarifs', name: 'get_all_tarifs', methods: ['GET'])]
    public function getAllTarifs(EntityManagerInterface $entityManager): JsonResponse
    {
        //tableau vide pour stocker la grille de tarif
        $result = [];
        // on récupère tous les produits
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        // pour chaque produit
        foreach ($produits as $produit) {
            // on récupère tous les tarifs en utilisant une méthode personnalisée 
            // la méthode classe les tarifs suivant le seuil de quantité dans l'ordre décroissant
            // la fonction JS getPrixUnitaire() récupèrera le tarif correspondant au seuil le plus élevé atteint par la quantité
            // if (quantite >= tarif.seuil)
            $tarifs = $entityManager->getRepository(Tarif::class)->findTarifsByProduit($produit);
            // on transforme les objets Tarif récupérés en tableaux pour le JSON en récupérant uniquement les champs nécessaires
            $result[$produit->getId()] = array_map(fn($tarif) => [
                'seuil' => $tarif->getSeuilQuantite(),
                'prix' => $tarif->getPrixUnitaire(),
            ], $tarifs);
        }
        
        // on renvoi au format JSON
        return new JsonResponse(['tarifs' => $result]);
    }

#[Route('/commande/livraison', name: 'livraison')]
public function adresseLivraison(EntityManagerInterface $entityManager): Response{

    }
}






