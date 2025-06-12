console.log("JS panier chargé !");

// on importe le fichier CSS pour la page panier
import '../styles/panier.css';

// on importe les fonctions nécessaire au calcul dynamique du prix
import { chargerTarifsGlobaux, getPrixUnitaire } from './calcul-prix';

// Quand la page est complètement chargée, on lance l'initialisation
document.addEventListener('DOMContentLoaded', initPage);

async function initPage () {
    await chargerTarifsGlobaux(); // on charge les grilles de tarifs (appel API)
    affichagePrixExemplaire();
}

function affichagePrixExemplaire() {

    // on récupère les div contenant les exemplaires mis dans le panier
    const articles = document.querySelectorAll('.article');
    
    // pour chacune des divs récupérées
    articles.forEach(article => {

        // on récupère la <div class="exemplaire-info"> qui contient l'exemplaire choisi
        const exemplaireDiv = article.querySelector('.exemplaire-info');
        // on récupère l'id du produit contenue dans l'attribut data-produit de la div
        const produitId = exemplaireDiv.dataset.produit;
        // on récupère le champ de quantité du formulaire
        const quantiteInput = article.querySelector('.quantite-input');
        // on récupère l'élement qui affiche le prix unitaire
        const prixUnitaireDisplay = article.querySelector('#prix-unitaire');
        // on récupère l'élement qui affiche le prix total
        const prixTotalDisplay = article.querySelector('#prix-total');
       
        quantiteInput.addEventListener('input', updatePrixTotal);

        function updatePrixTotal() {
        // on récupère la quantité entrée, convertie en nombre entier
        const quantite = parseInt(quantiteInput.value);
        
        // on récupère le prix unitaire avec la fonction importée dédiée
        const prixUnitaire = getPrixUnitaire(produitId, quantite);

        // calcul du prix total
        const total = quantite * prixUnitaire;
        // console.log(prixUnitaire);

        prixUnitaireDisplay.textContent = `${prixUnitaire.toFixed(2)} €`;
        prixTotalDisplay.textContent = `${total.toFixed(2)} €`;
        
        }
        // affichage initial
        updatePrixTotal();
        console.log('Affichage des prix');
    });
}



