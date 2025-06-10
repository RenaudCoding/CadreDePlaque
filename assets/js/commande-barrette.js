console.log("JS choix de l'exemplaire de barrette chargé !");

// on importe le fichier CSS pour la page de commande de barrette
import '../styles/commande-barrette.css';

// on importe les fonctions nécessaire au calcul dynamique du prix
import { chargerTarifsGlobaux, getPrixUnitaire, setupQuantityInputs } from './calcul-prix';

// Quand la page est complètement chargée, on lance l'initialisation
document.addEventListener('DOMContentLoaded', initPage);

// On appelle les 2 fonctions nécessaires au fonctionnement JS de la page
function initPage() {
    initChoixExemplaire();
    affichagePrix();
}

// Mise en place des élements nécessaires au fonctionnement du choix d'exemplaire
function initChoixExemplaire() {
    // on récupère tous les boutons avec la classe "choisir-exemplaire"
    const buttons = document.querySelectorAll('.choisir-exemplaire');
    // on récupère le champ du formulaire dont l'id est "commande_barrette_exemplaire"
    const exemplaireField = document.querySelector('#commande_barrette_exemplaire');
    // on récupère la zone ou on affichera l'exemplaire choisi
    const container = document.querySelector('.exemplaire-commande');
    
    // A servi pour tester l'accès au script
    // si le champ du formulaire n'est pas trouvé, message d'erreur
    if (!exemplaireField) {
        console.warn("Le champ #commande_barrette_exemplaire n'a pas été trouvé.");
        return;
    }

    // pour chaque bouton, on écoute le clic et on appel la fonction d'action suite au clic
    buttons.forEach(button => {
        button.addEventListener('click', () => handleChoixClick(button, exemplaireField, container));
    });
}

// Actions après un clic sur un bouton de selection d'un exemplaire
function handleChoixClick(button, exemplaireField, container) {

    // Gestion du choix de l'exemplaire et affichage
    // on retrouve la <div class='exemplaire'> contenant le bouton sur lequel on a cliqué
    const exemplaire = button.closest('.exemplaire');
    // on récupère dans cette div la <div class='exemplaire-info'> contenant les attributs 'data-id' et 'data-produit'
    const exemplaireDiv = exemplaire.querySelector('.exemplaire-info');
    // on récupère l'id indiquée dans l'attribut 'data-id'
    const id = exemplaireDiv.dataset.id;
    // on transmet l'id dans le champ exemplaireField du formulaire
    exemplaireField.value = id;   
    // on affiche la <div class='exemplaire-info'> dans la zone d'affichage
    container.innerHTML = exemplaireDiv.outerHTML;

    // Gestion de la validation du formulaire
    // on récupère l'élement correspondant au champ quantité
    const quantiteInput = document.getElementById('commande_barrette_quantite');
    // on récupère l'élement checkbox
    const checkbox = document.getElementById('commande_barrette_validation');
    // on récupère le bouton de validation du formulaire "ajouter au panier"
    const submitBtn = document.getElementById('commande_barrette_submit');

    // on active le champ de saisi de la quantité
    quantiteInput.disabled = false;

    //on écoute le champ quantité pour activé/desactivé la checkbox en fonction de la quantité indiquée
    quantiteInput.addEventListener('input', function () {
        // on récupère la quantité (en base 10 - facultatif car par défaut)
        const quantite = parseInt(quantiteInput.value, 10);
        // si la quantité est renseignée et supérieure à 0
        if (!isNaN(quantite) && quantite > 0) {
                // on active la checkbox
                checkbox.disabled = false;        
        } 
        //sinon :
        else {
            // on désactive la checkbox (on s'assure qu'elle est désactivée)
            checkbox.disabled = true;
            // on décoche la checkbox (on s'assure qu'elle est décochée)
            checkbox.checked = false;
            // on désactive le bouton submit
            submitBtn.disabled = true;    
        }
    });

    // on écoute la checkbox et on change l'état du bouton "ajouter au panier" en fonction de l'état de la checkbox
    checkbox.addEventListener('change', function () {
        // le bouton submit est disabled quand la checkbox n'est pas cochée
        submitBtn.disabled = !this.checked;
    });

    // on s'assure que le prix est à jour si la quantité est déjà renseignée au moment du choix de l'exemplaire
    affichagePrix();
}

// affichage dynamique du prix
function affichagePrix() {
    // on récupère la <div class="exemplaire-commande"> qui est la zone d'affichage de l'exemplaire sélectionné
    const container = document.querySelector('.exemplaire-commande');
    // on récupère dans ce conteneur la <div class="exemplaire-info"> qui contient l'exemplaire choisi
    // cette div à les atttributs "data-id" et "data-produit"
    const exemplaireInfoEl = container.querySelector('.exemplaire-info');
    // on récupère le champ de quantité du formulaire
    const quantiteInput = document.getElementById('commande_barrette_quantite');
    // on récupère l'élement qui affiche le prix unitaire
    const prixUnitaireDisplay = document.getElementById('prix-unitaire');
    // on récupère l'élement qui affiche le prix total
    const prixTotalDisplay = document.getElementById('prix-total');

    // affichage dans la console pour débogage
    // console.log(exemplaireInfoEl, quantiteInput, prixTotalDisplay, prixUnitaireDisplay);

    // si un des éléments est manquant, on arrête le script
    if (!exemplaireInfoEl || !quantiteInput || !prixUnitaireDisplay || !prixTotalDisplay) return;

    // on récupère l'id du produit depuis l'attribut "data-produit"
    const produitId = exemplaireInfoEl.dataset.produit;
    
    // mise à jour de l'affichage des prix en fonction de la quantité
    function updatePrixTotal() {

        // on récupère la quantité entrée, convertie en nombre entier (en base 10 - facultatif car par défaut)
        const quantite = parseInt(quantiteInput.value, 10);

        // si la quantité est vide ou nulle, les prix sont mis à 0
        if (isNaN(quantite) || quantite <= 0) {
            prixUnitaireDisplay.textContent = "Prix unitaire : 0.00 €";
            prixTotalDisplay.textContent = "Prix Total : 0.00 €";
            return;
        }

        // on récupère le prix unitaire avec la fonction importée dédiée
        const prixUnitaire = getPrixUnitaire(produitId, quantite);
        // calcul du prix total
        const total = quantite * prixUnitaire;

        // on met à jour l'affichage des prix formatés
        prixUnitaireDisplay.textContent = `Prix unitaire : ${prixUnitaire.toFixed(2)} €`;
        prixTotalDisplay.textContent = `Prix Total : ${total.toFixed(2)} €`;

    }

    // on charge les grilles de tarifs (appel API) puis
    chargerTarifsGlobaux().then(() => {
        // on lance le calcul du prix total dès qu'on change la quantité
        quantiteInput.addEventListener('input', updatePrixTotal);
        // affichage initial
        updatePrixTotal();
    });
}

