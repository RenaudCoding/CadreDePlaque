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
    affichagePrixAvant();
    affichagePrixArriere();
    affichagePrixTotal()
}

// Fonction d'initialisation
function initChoixExemplaire() {

    // on charge les grilles de tarifs (appel API)
    chargerTarifsGlobaux()

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

    // Pour chaque bouton de sélection, on ajoute un écouteur d'événement
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

    // exemplaire avant
    if(produit == 2) {
        // on transmet l'id dans le champ exemplaireField du formulaire
        exemplaireFieldAvant.value = id;   
        // on affiche la <div class='exemplaire-info'> dans la zone d'affichage
        containerAvant.innerHTML = exemplaireDiv.outerHTML;
        // on active le champ de saisi de la quantité avant
        quantiteInputAvant.disabled = false;
    }
    
    // exemplaire arrière
    if(produit == 3) {
        // on transmet l'id dans le champ exemplaireField du formulaire
        exemplaireFieldArriere.value = id;   
        // on affiche la <div class='exemplaire-info'> dans la zone d'affichage
        containerArriere.innerHTML = exemplaireDiv.outerHTML;
        // on active le champ de saisi de la quantité arrière
        quantiteInputArriere.disabled = false;
    }
   
    // à utiliser pour lorsque le retrait d'un exemplaire sera mis en place
    containerAvant.addEventListener('change', affichagePrixAvant);
    containerArriere.addEventListener('change', affichagePrixArriere);


    // Gestion de la validation du formulaire
    // on récupère l'élement checkbox
    const checkbox = document.getElementById('commande_cacheplaque_validation');
    // on récupère le bouton de validation du formulaire "ajouter au panier"
    const submitBtn = document.getElementById('commande_cacheplaque_submit');

    // on écoute les champs de quantité avant et arrière
    quantiteInputAvant.addEventListener('input', () => handleCheckSubmit(quantiteInputAvant, quantiteInputArriere));
    quantiteInputArriere.addEventListener('input', () => handleCheckSubmit(quantiteInputAvant, quantiteInputArriere));

    // fonction de gestion de la checkbox et du bouton submit
    function handleCheckSubmit() {
        // on récupère la valeur des champs de quantité avant et arrière
        const qAvant = parseInt(quantiteInputAvant.value, 10);
        const qArriere = parseInt(quantiteInputArriere.value, 10);

        // si les quantités avant et arrière existe et l'une d'entre elles est supérieure à 0
        if ((!isNaN(qAvant) || !isNaN(qArriere)) && (qAvant > 0 || qArriere > 0)) {
                // on active la checkbox
                checkbox.disabled = false;        
        }
        else {
            // on désactive la checkbox (on s'assure qu'elle est désactivée)
            checkbox.disabled = true;
            // on décoche la checkbox (on s'assure qu'elle est décochée)
            checkbox.checked = false;
            // on désactive le bouton submit
            submitBtn.disabled = true;    
        }
    }

    // on écoute la checkbox et on change l'état du bouton "ajouter au panier" en fonction de l'état de la checkbox
    checkbox.addEventListener('change', function () {
        // le bouton submit est disabled quand la checkbox n'est pas cochée
        submitBtn.disabled = !this.checked;
    });

    // on s'assure que le prix est à jour si la quantité est déjà renseignée au moment du choix de l'exemplaire
    affichagePrixTotal();
}   




function affichagePrixAvant() {
    // on récupère la <div class="exemplaire-commande"> qui est la zone d'affichage de l'exemplaire sélectionné
    const containerAvant = document.querySelector('.exemplaire-commande-avant');
    // on récupère dans ce conteneur la <div class="exemplaire-info"> qui contient l'exemplaire choisi
    // cette div à les atttributs "data-id" et "data-produit"
    const exemplaireInfoAvant = containerAvant.querySelector('.exemplaire-info');
    // on récupère le champ de quantité du formulaire
    const quantiteInputAvant = document.getElementById('commande_cacheplaque_quantiteAvant');
    // on récupère l'élement qui affiche le prix unitaire
    const prixUnitaireAvantDisplay = document.getElementById('prix-unitaire-avant');
    // on récupère l'élement qui affiche le prix total
    const prixAvantDisplay = document.getElementById('prix-avant');

    // affichage dans la console pour débogage
    // console.log(exemplaireInfoEl, quantiteInput, prixTotalDisplay, prixUnitaireDisplay);

    // si un des éléments est manquant, on arrête le script
    if (!exemplaireInfoAvant || !quantiteInputAvant || !prixUnitaireAvantDisplay || !prixAvantDisplay) return;

    // on récupère l'id du produit depuis l'attribut "data-produit"
    const produitIdAvant = exemplaireInfoAvant.dataset.produit;
    
    // mise à jour de l'affichage des prix en fonction de la quantité
    function updatePrixTotalAvant() {

        // on récupère la quantité entrée, convertie en nombre entier (en base 10 - facultatif car par défaut)
        const qAvant = parseInt(quantiteInputAvant.value);

        // si la quantité est vide ou nulle, les prix sont mis à 0
        if (isNaN(qAvant) || qAvant <= 0) {
            prixUnitaireAvantDisplay.textContent = "Prix unitaire avant : 0.00 €";
            prixAvantDisplay.textContent = "Total Avant : 0.00 €";
            return;
        }

        // on récupère le prix unitaire avec la fonction importée dédiée
        const prixUnitaireAvant = getPrixUnitaire(produitIdAvant, qAvant);
        // calcul du prix total
        const totalAvant = qAvant * prixUnitaireAvant;

        // on met à jour l'affichage des prix formatés
        prixUnitaireAvantDisplay.textContent = `Prix unitaire avant : ${prixUnitaireAvant.toFixed(2)} €`;
        prixAvantDisplay.textContent = `Total Avant : ${totalAvant.toFixed(2)} €`;
        return totalAvant;
    }

    // on lance le calcul du prix total dès qu'on change la quantité
    quantiteInputAvant.addEventListener('input', affichagePrixAvant);
    // on récupère le total pour l'avant pour le calcul du total global
    const totalAvant = updatePrixTotalAvant();
    return totalAvant;
}


function affichagePrixArriere() {
    // on récupère la <div class="exemplaire-commande"> qui est la zone d'affichage de l'exemplaire sélectionné
    const containerArriere = document.querySelector('.exemplaire-commande-arriere');
    // on récupère dans ce conteneur la <div class="exemplaire-info"> qui contient l'exemplaire choisi
    // cette div à les atttributs "data-id" et "data-produit"
    const exemplaireInfoArriere = containerArriere.querySelector('.exemplaire-info');
    // on récupère le champ de quantité du formulaire
    const quantiteInputArriere = document.getElementById('commande_cacheplaque_quantiteArriere');
    // on récupère l'élement qui affiche le prix unitaire
    const prixUnitaireArriereDisplay = document.getElementById('prix-unitaire-arriere');
    // on récupère l'élement qui affiche le prix total
    const prixArriereDisplay = document.getElementById('prix-arriere');

    // affichage dans la console pour débogage
    // console.log(exemplaireInfoEl, quantiteInput, prixTotalDisplay, prixUnitaireDisplay);

    // si un des éléments est manquant, on arrête le script
    if (!exemplaireInfoArriere || !quantiteInputArriere || !prixUnitaireArriereDisplay || !prixArriereDisplay) return;

    // on récupère l'id du produit depuis l'attribut "data-produit"
    const produitIdArriere = exemplaireInfoArriere.dataset.produit;
    
    // mise à jour de l'affichage des prix en fonction de la quantité
    function updatePrixTotalArriere() { // on récupère la quantité entrée, convertie en nombre entier (en base 10 - facultatif car par défaut)
        const qArriere = parseInt(quantiteInputArriere.value);

        // si la quantité est vide ou nulle, les prix sont mis à 0
        if (isNaN(qArriere) || qArriere <= 0) {
            prixUnitaireArriereDisplay.textContent = "Prix unitaire arriere : 0.00 €";
            prixArriereDisplay.textContent = "Total Arriere : 0.00 €";
            return;
        }

        // on récupère le prix unitaire avec la fonction importée dédiée
        const prixUnitaireArriere = getPrixUnitaire(produitIdArriere, qArriere);
        // calcul du prix total
        const totalArriere = qArriere * prixUnitaireArriere;

        // on met à jour l'affichage des prix formatés
        prixUnitaireArriereDisplay.textContent = `Prix unitaire arriere : ${prixUnitaireArriere.toFixed(2)} €`;
        prixArriereDisplay.textContent = `Total Arriere : ${totalArriere.toFixed(2)} €`;
        return totalArriere;
        
    }
    // on lance le calcul du prix total dès qu'on change la quantité
    quantiteInputArriere.addEventListener('input', affichagePrixArriere);
    // on récupère le total pour l'arrière pour le calcul du total global
    const totalArriere = updatePrixTotalArriere();
    return totalArriere;

}




// affichage du prix total
// en utilisant uniquement de la logique
function affichagePrixTotal() {
   
    // on récupère l'élement qui affiche le prix total
    const prixTotalDisplay = document.getElementById('prix-total');
    // on récupère les totaux avant et arrière
    const totalAvant = affichagePrixAvant();
    const totalArriere = affichagePrixArriere();

    // affichage pour debogage
    // console.log(totalAvant, totalArriere);
    
    // on récupère les champs de quantité avant et arrière
    const quantiteInputAvant = document.getElementById('commande_cacheplaque_quantiteAvant');
    const quantiteInputArriere = document.getElementById('commande_cacheplaque_quantiteArriere');
    // on les écoute pour mettre à jour le prix total global
    quantiteInputAvant.addEventListener('input', affichagePrixTotal);
    quantiteInputArriere.addEventListener('input', affichagePrixTotal);

    // calcul du total global
    // si le total avant et le total arrière ne sont pas définis
    if (isNaN(totalAvant) && isNaN(totalArriere)) {
            prixTotalDisplay.textContent = "Total : 0.00 €";
            return;
    }
    // sinon
    else {
        // si le total avant n'est pas défini
        if (isNaN(totalAvant)) {
            //le total global correspond au total arrière
            prixTotalDisplay.textContent = `Total : ${totalArriere.toFixed(2)} €`;
            return;
        }
        // si le total arrière n'est pas défini
        if (isNaN(totalArriere)) {
            //le total global correspond au total avant
            prixTotalDisplay.textContent = `Total : ${totalAvant.toFixed(2)} €`;
            return;
        }
        // on calcul le prix total
        const prixTotal = totalAvant + totalArriere;
        // on met à jour l'affichage du prix total
        prixTotalDisplay.textContent = `Total : ${prixTotal.toFixed(2)} €`;
    }

}



// 2ème version de la fonction d'affichage du prix total
// en utilisant une valeure numérique
/*
function affichagePrixTotal() {
   
    // on récupère l'élement qui affiche le prix total
    const prixTotalDisplay = document.getElementById('prix-total');

    // on affecte la valeur par défaut au total avant et total arrière
    let totalAvant = 0;
    let totalArriere = 0;
    
    // si la fonction qui affiche le total avant renvoi une valeur
    if (!isNaN(affichagePrixAvant())) {
        // on récupère la valeur
        totalAvant = affichagePrixAvant();
    }

    // si la fonction qui affiche le total arrière renvoi une valeur
        // on récupère la valeur
    if (!isNaN(affichagePrixArriere())) {
        totalArriere = affichagePrixArriere();
    }
    
    // on récupère les champs de quantité avant et arrière
    const quantiteInputAvant = document.getElementById('commande_cacheplaque_quantiteAvant');
    const quantiteInputArriere = document.getElementById('commande_cacheplaque_quantiteArriere');
    // on les écoute pour mettre à jour le prix total global
    quantiteInputAvant.addEventListener('input', affichagePrixTotal);
    quantiteInputArriere.addEventListener('input', affichagePrixTotal);
    
    // on calcul le prix total
    const prixTotal = totalAvant + totalArriere;
    // on met à jour l'affichage du prix total
    prixTotalDisplay.textContent = `Total : ${prixTotal.toFixed(2)} €`;
      
}
*/