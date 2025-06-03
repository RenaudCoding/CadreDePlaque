console.log("JS choix de l'exemplaire de barrette chargé !");

// on importe le fichier CSS pour la page de commande de barrette
import '../styles/commande-barrette.css';

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

    // A servi pour tester l'accès au script
    // si le champ du formulaire n'est pas trouvé, message d'erreur
    if (!exemplaireField) {
        console.warn("Le champ #commande_barrette_exemplaire n'a pas été trouvé.");
        return;
    }

    // on créer la zone d'affichage des infos de l'exemplaire
    const selectedDisplay = createSelectedDisplayContainer(exemplaireField);

    // pour chaque bouton, on écoute le clic et on appel la fonction d'action suite au clic
    buttons.forEach(button => {
        button.addEventListener('click', () => handleChoixClick(button, exemplaireField, selectedDisplay));
    });

    // Gestion de la validation du formulaire
    // on récupère l'élement checkbox
    const checkbox = document.getElementById('commande_barrette_validation');
    // on récupère le bouton de validation du formulaire "ajouter au panier"
    const submitBtn = document.getElementById('commande_barrette_submit');

    // on écoute la checkbox et on change l'état du bonton "ajouter au panier" en fonction de l'état de la checkbox
    checkbox.addEventListener('change', function () {
        // le bouton submit est disabled quand la checkbox n'est pas cochée
        submitBtn.disabled = !this.checked;
    });

}

// Création du conteneur qui sera affiché
function createSelectedDisplayContainer(exemplaireField) {
    // on créer un conteneur contenant une div
    const container = document.createElement('div');

    container.style.border = '1px solid #ccc';
    container.style.padding = '1em';
    container.style.marginBottom = '1em';
    container.style.height = '110px';
    container.innerHTML = '<p style="margin: 0; color: #666;">Aucun exemplaire sélectionné.</p>';

    // on insert ce conteneur au début du formulaire contenant le champ "exemplaireField"
    exemplaireField.closest('form').prepend(container);
    
    // on retourne le conteneur pour qu'il soit visible
    return container;
}

// Actions après un clic
function handleChoixClick(button, exemplaireField, selectedDisplay) {
    // on retrouve la div avec la class=exemplaire contenant le bouton sur lequel on a cliqué
    const exemplaireDiv = button.closest('.exemplaire');
    // on défini une constante "id" à laquelle on affecte la valeur indiquée dans l'attribut "data-id" de la div récupérée
    const id = exemplaireDiv.dataset.id;

    // on transmet l'id dans le champ exemplaireField du formulaire
    exemplaireField.value = id;
    // on active la checkbox du formulaire  
    document.getElementById('commande_barrette_validation').disabled = false;
    
    // on récupère la div avec la class=exemplaire-info
    const info = exemplaireDiv.querySelector('.exemplaire-info');
    // on l'affiche dans le conteneur créer dans la fonction
    selectedDisplay.innerHTML = info.outerHTML;
}

// affichage dynamique du prix
function affichagePrix() {
    // on récupère l'élément correspondant à la quantité de barrettes commandées
    const quantiteInput = document.getElementById('commande_barrette_quantite');
    // on récupère l'élément contenant le prix unitaire
    const prixUnitaireEl = document.getElementById('prix-unitaire');
    // on réupère l'élément où sera affiché le prix total
    const prixTotalDisplay = document.getElementById('prix-total');

    // on vérifie que les éléments essentiels sont bien présents dans le DOM
    if (!quantiteInput || !prixTotalDisplay) return;

    // on convertit le prix unitaire en nombre flottant (ou 0 si invalide)
    const prixUnitaire = parseFloat(prixUnitaireEl.value);

    // Fonction qui calcule et affiche dynamiquement le prix total
    function updatePrixTotal() {
        // on récupère la quantité saisie (ou 0 si vide/invalide)
        const quantite = parseInt(quantiteInput.value) || 0;
        // on Calcule le prix total
        const total = quantite * prixUnitaire;
        // on affiche le résultat formaté avec 2 décimales et un symbole euro
        prixTotalDisplay.textContent = total.toFixed(2) + ' €';
    }

    // écouteur d'événement pour mettre à jour le prix à chaque changement de la quantité
    quantiteInput.addEventListener('input', updatePrixTotal);

    // Appel initial pour afficher le prix dès le chargement de la page
    updatePrixTotal();
}