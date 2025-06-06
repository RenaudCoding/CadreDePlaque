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

// Actions après un clic
function handleChoixClick(button, exemplaireField, container) {
    // on retrouve la <div class='exemplaire'> contenant le bouton sur lequel on a cliqué
    const exemplaire = button.closest('.exemplaire');
    // on récupère dans cette div la <div class='exemplaire-info'> contenant les attributs 'data-id' et 'data-produit'
    const exemplaireDiv = exemplaire.querySelector('.exemplaire-info');
    // on défini une constante "id" à laquelle on affecte la valeur indiquée dans l'attribut 'data-id'
    const id = exemplaireDiv.dataset.id;;
    
    // on transmet l'id dans le champ exemplaireField du formulaire
    exemplaireField.value = id;
    // on active la checkbox du formulaire  
    document.getElementById('commande_barrette_validation').disabled = false;
    
    // on affiche la <div class='exemplaire-info'> dans la zone d'affichage
    container.innerHTML = exemplaireDiv.outerHTML;
    affichagePrix();
    
}

// affichage dynamique du prix
function affichagePrix() {
        const container = document.querySelector('.exemplaire-commande');
        const exemplaireInfoEl = container.querySelector('.exemplaire-info');
        const quantiteInput = document.getElementById('commande_barrette_quantite');
        const prixTotalDisplay = document.getElementById('prix-total');
        const prixUnitaireDisplay = document.getElementById('prix-unitaire');

console.log(exemplaireInfoEl, quantiteInput, prixTotalDisplay, prixUnitaireDisplay);



        if (!exemplaireInfoEl || !quantiteInput || !prixTotalDisplay || !prixUnitaireDisplay) return;

        const produitId = exemplaireInfoEl.dataset.produit;
        
        function updatePrixTotal() {
            const quantite = parseInt(quantiteInput.value, 10);
            if (isNaN(quantite) || quantite <= 0) {
                prixTotalDisplay.textContent = "0.00 €";
                prixUnitaireDisplay.textContent = "Prix unitaire : 0.00 €";
                return;
            }

            fetch(`/get-prix?produit_id=${produitId}&quantite=${quantite}`)
                .then(response => response.json())
                .then(data => {
                    if (data.prix_unitaire) {
                        const total = quantite * data.prix_unitaire;
                        prixTotalDisplay.textContent = total.toFixed(2) + ' €';
                        prixUnitaireDisplay.textContent = 'Prix unitaire : ' + data.prix_unitaire.toFixed(2) + ' €';
                    } else {
                        prixTotalDisplay.textContent = "Tarif introuvable";
                        prixUnitaireDisplay.textContent = "Tarif introuvable";
                    }
                })
                .catch(err => {
                    prixTotalDisplay.textContent = "Erreur serveur";
                    prixUnitaireDisplay.textContent = "Erreur serveur";
                    console.error(err);
                });
        }

        // Lancer le calcul du prix total dès qu'on change la quantité
        quantiteInput.addEventListener('input', updatePrixTotal);
        updatePrixTotal(); // Appel initial
    }
