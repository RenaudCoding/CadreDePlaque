console.log("JS panier chargé !");

// on importe le fichier CSS pour la page panier
import '../styles/panier.css';

// on importe les fonctions nécessaire au calcul dynamique du prix
import { chargerTarifsGlobaux, getPrixUnitaire } from './calcul-prix';

// Quand la page est complètement chargée, on lance l'initialisation
document.addEventListener('DOMContentLoaded', initPage);

// fonction asynchrone
async function initPage () {
    // on attend que les grilles de tarifs soient chargées (appel API)
    await chargerTarifsGlobaux(); 
    affichagePrixExemplaire();
}

// on créer objet JS qui servira à stocker les prix calculés pour chaque article
// clé = id exemplaire, valeur = prix total calculé
// on utilisera ce tableau pour calculer le prix total du panier
// on enverra ce tableau coté serveur pour comparer le calcul JS avec le calcul serveur
// on persitera le calcul fait côté serveur (en cas de manipulation malveillante côté client)
// la comparaison permet de s'assurer de la cohérence entre ce qui est affiché et ce qui est enregistré en BDD
const panierPrix = {}; 

function affichagePrixExemplaire() {

    // on récupère les div contenant les exemplaires mis dans le panier
    const articles = document.querySelectorAll('.article');

    // pour chacunes des divs récupérées
    articles.forEach(article => {

        // on récupère la <div class="exemplaire-info"> qui contient l'exemplaire choisi
        const exemplaireDiv = article.querySelector('.exemplaire-info');
        // on récupère l'id du produit contenue dans l'attribut data-produit de la div
        const produitId = exemplaireDiv.dataset.produit;
        // on récupère l'id de l'exemplaire qu'on utilisera comme clé dans l'objet JS qui stockera les prix
        const exemplaireId = exemplaireDiv.dataset.id;
        // on récupère le champ de quantité du formulaire
        const quantiteInput = article.querySelector('.quantite-input');
        // on récupère l'élement qui affiche le prix unitaire
        const prixUnitaireDisplay = article.querySelector('#prix-unitaire');
        // on récupère l'élement qui affiche le prix total
        const prixTotalDisplay = article.querySelector('#prix-total');
       
        // on écoute les champs de quantité
        quantiteInput.addEventListener('input', updatePrixTotal);

        // fonction de mise à jour des prix
        function updatePrixTotal() {
            // on récupère la quantité entrée, convertie en nombre entier
            const quantite = parseInt(quantiteInput.value);
            // on récupère le prix unitaire avec la fonction importée dédiée
            const prixUnitaire = getPrixUnitaire(produitId, quantite);

            // on initialise le total à 0
            let total = 0;

            // si la quantité existe (champ non vide)
            if(!isNaN(quantite)) {
                // calcul du prix total
                total = quantite * prixUnitaire;
            }

            // on met à jour l'affichage du prix
            prixUnitaireDisplay.textContent = `${prixUnitaire.toFixed(2)} €`;
            prixTotalDisplay.textContent = `${total.toFixed(2)} €`;
            
            // on mets à jour le prix dans le tableau qui sera envoyé en JSON
            panierPrix[exemplaireId] = total;

            // on (re)calcule le total du panier
            updateTotalPanier();

            // fonction de calcul du total du panier
            function updateTotalPanier() {
                // on initialise le total panier à 0
                let totalPanier = 0;

                // pour chaque exemplaire présent dans le tableau
                for (const exemplaireId in panierPrix) {
                    //on récupère le prix
                    const prix = panierPrix[exemplaireId];

                    // si le prix existe
                    if(!isNaN(prix)) {
                        // on additionne le prix au total panier
                        totalPanier += prix;
                    }
                }
                // on met à jour l'affichage du total panier
                const totalPanierDisplay = document.getElementById('prix-total-panier');
                totalPanierDisplay.textContent = `${totalPanier.toFixed(2)} €`;
            }
        }

        // affichage initial
        updatePrixTotal();
        console.log('Affichage des prix');
    });
}



