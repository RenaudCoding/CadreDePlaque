console.log("JS choix de l'exemplaire de cacheplaque chargé !");

// on importe le fichier CSS pour la page de commande de barrette
import '../styles/commande-cacheplaque.css';

// on importe les fonctions nécessaire au calcul dynamique du prix
import { chargerTarifsGlobaux, getPrixUnitaire, setupQuantityInputs } from './calcul-prix';

// Quand la page est complètement chargée, on lance l'initialisation
document.addEventListener('DOMContentLoaded', initPage);

// On appelle les 2 fonctions nécessaires au fonctionnement JS de la page
function initPage(){
    initChoixExemplaire();
    affichagePrix();
}

// Fonction d'initialisation
function initChoixExemplaire() {
    // On récupère tous les boutons permettant de choisir un exemplaire
    const buttons = document.querySelectorAll('.choisir-exemplaire');

     // On récupère les deux champs cachés du formulaire pour les exemplaires avant et arrière
    const exemplaireFieldAvant = document.querySelector('#commande_cacheplaque_exemplaireAvant');
    const exemplaireFieldArriere = document.querySelector('#commande_cacheplaque_exemplaireArriere');
    // on récupère les zones ou on affichera l'exemplaire avant et arrière
    const containerAvant = document.querySelector('.exemplaire-commande-avant');
    const containerArriere = document.querySelector('.exemplaire-commande-arriere');

    // Si aucun des deux champs n'est trouvé, on affiche un message d'avertissement dans la console
    if (!exemplaireFieldAvant && !exemplaireFieldArriere) {
        console.warn("Les champs exemplaire n'ont pas été trouvés.");
        return; // On arrête le script ici
    }

    // Pour chaque bouton de sélection, on ajoute un écouteur d'événement et on map
    buttons.forEach(button => {
        button.addEventListener('click', () =>
            handleChoixClick(button, exemplaireFieldAvant, containerAvant, exemplaireFieldArriere, containerArriere));
    });

}


// Gestion du clic sur un bouton de sélection
function handleChoixClick(button, exemplaireFieldAvant, containerAvant, exemplaireFieldArriere, containerArriere) {
    // On récupère la div contenant le bouton cliqué (et donc l'exemplaire associé)
    const exemplaire = button.closest('.exemplaire');
    // on récupère dans cette div la <div class='exemplaire-info'> contenant les attributs 'data-id' et 'data-produit'
    const exemplaireDiv = exemplaire.querySelector('.exemplaire-info');

     // On lit les données stockées dans les attributs data-* de la div
    const id = exemplaireDiv.dataset.id; // ID de l'exemplaire
    const produit = exemplaireDiv.dataset.produit; // Type de produit (2 ou 3)

    // on récupère les élements correspondant au champ quantité avant et arrière
    const quantiteInputAvant = document.getElementById('commande_cacheplaque_quantiteAvant');
    const quantiteInputArriere = document.getElementById('commande_cacheplaque_quantiteArriere');

    if(produit == 2) {
        // on transmet l'id dans le champ exemplaireField du formulaire
        exemplaireFieldAvant.value = id;   
        // on affiche la <div class='exemplaire-info'> dans la zone d'affichage
        containerAvant.innerHTML = exemplaireDiv.outerHTML;
        // on active le champ de saisi de la quantité avant
        quantiteInputAvant.disabled = false;
    }
    
    if(produit == 3) {
        // on transmet l'id dans le champ exemplaireField du formulaire
        exemplaireFieldArriere.value = id;   
        // on affiche la <div class='exemplaire-info'> dans la zone d'affichage
        containerArriere.innerHTML = exemplaireDiv.outerHTML;
        // on active le champ de saisi de la quantité arrière
        quantiteInputArriere.disabled = false;
    }
   
    // Gestion de la validation du formulaire
    // on récupère l'élement checkbox
    const checkbox = document.getElementById('commande_cacheplaque_validation');
    // on récupère le bouton de validation du formulaire "ajouter au panier"
    const submitBtn = document.getElementById('commande_cacheplaque_submit');


    //on écoute le champ quantité avant pour activé/desactivé la checkbox en fonction de la quantité indiquée
    quantiteInputAvant.addEventListener('input', function () {
        // on récupère la quantité (en base 10 - facultatif car par défaut)
        const quantite = parseInt(quantiteInputAvant.value, 10);
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


    //on écoute le champ quantité arrière pour activé/desactivé la checkbox en fonction de la quantité indiquée
    quantiteInputArriere.addEventListener('input', function () {
        // on récupère la quantité (en base 10 - facultatif car par défaut)
        const quantite = parseInt(quantiteInputArriere.value, 10);
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

    
}   
/*
// affichage dynamique du prix
function affichagePrix() {
    // on regroupe tous les éléments DOM nécessaires dans un objet pour une meilleure organisation
    const elements = {
        // Champ de saisie pour la quantité avant
        quantiteAvant: document.getElementById('commande_cacheplaque_quantiteAvant'),
        // Champ de saisie pour la quantité arrière
        quantiteArriere: document.getElementById('commande_cacheplaque_quantiteArriere'),
        // Prix unitaire des caches plaque avant, converti en float (ou 0 si non défini ou invalide)
        prixUnitaireAvant: parseFloat(document.getElementById('prix-unitaire-avant')?.value) || 0,
        // Prix unitaire des caches plaques arrière, converti en float (ou 0 si non défini ou invalide)
        prixUnitaireArriere: parseFloat(document.getElementById('prix-unitaire-arriere')?.value) || 0,
        // Élément qui affichera le prix total des caches plaque avant
        totalAvant: document.getElementById('prix-avant'),
        // Élément qui affichera le prix total des caches plaque arrière
        totalArriere: document.getElementById('prix-arriere'),
        // Élément qui affichera le prix total global
        totalGlobal: document.getElementById('prix-total')
    };

    // on vérifie que tous les éléments nécessaires sont bien présents dans le DOM
    if (!elements.quantiteAvant || !elements.quantiteArriere || 
        !elements.totalAvant || !elements.totalArriere || !elements.totalGlobal) return;

    // Fonction qui calcule et met à jour tous les prix
    const updatePrixTotal = () => {
        // on récupère la quantité saisie pour les caches plaque avant (ou 0 si vide/invalide)
        const qAvant = parseInt(elements.quantiteAvant.value) || 0;
        // on récupère la quantité saisie pour les caches plaque arrière (ou 0 si vide/invalide)
        const qArriere = parseInt(elements.quantiteArriere.value) || 0;

        // on calcule le total pour les caches plaque avant
        const totalAvant = qAvant * elements.prixUnitaireAvant;
        // on calcule le total pour les plaques arrière
        const totalArriere = qArriere * elements.prixUnitaireArriere;
        // on calcule le total général (avant + arrière)
        const total = totalAvant + totalArriere;

        // on met à jour l'affichage des totau avec 2 décimales
        elements.totalAvant.textContent = `${totalAvant.toFixed(2)} €`;
        elements.totalArriere.textContent = `${totalArriere.toFixed(2)} €`;
        elements.totalGlobal.textContent = `${total.toFixed(2)} €`;
    };

    // écouteur d’événement sur la quantité avant pour recalculer à chaque modification
    elements.quantiteAvant.addEventListener('input', updatePrixTotal);
    // écouteur d’événement sur la quantité arrière pour recalculer à chaque modification
    elements.quantiteArriere.addEventListener('input', updatePrixTotal);

    // Exécute la fonction une première fois au chargement pour afficher les prix initiaux
    updatePrixTotal();
}
*/











