console.log("JS choix de l'exemplaire de cacheplaque chargé !");

// on importe le fichier CSS pour la page de commande de barrette
import '../styles/commande-cacheplaque.css';

// Quand la page est complètement chargée, on lance l'initialisation
document.addEventListener('DOMContentLoaded', initPage);

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

    // Si aucun des deux champs n'est trouvé, on affiche un message d'avertissement dans la console
    if (!exemplaireFieldAvant && !exemplaireFieldArriere) {
        console.warn("Les champs exemplaire n'ont pas été trouvés.");
        return; // On arrête le script ici
    }

    // On crée les conteneurs d'affichage pour les informations sélectionnées
    // L'ordre d'appel est inversé car avec prepend(container), les conteneurs s'ajoutent toujours sur le dessus
    // Avec cet ordre, on a l'exemplaire avant qui s'affiche au dessus de l'exemplaire arrière
    const displays = {
        3: exemplaireFieldArriere ? createDisplayContainer(exemplaireFieldArriere, 'arrière') : null,
        2: exemplaireFieldAvant ? createDisplayContainer(exemplaireFieldAvant, 'avant') : null
        
    };

    // Pour chaque bouton de sélection, on ajoute un écouteur d'événement "click"
    buttons.forEach(button => {
        button.addEventListener('click', () =>
            handleChoixClick(button, {
                2: { field: exemplaireFieldAvant, display: displays[2] },
                3: { field: exemplaireFieldArriere, display: displays[3] }
            })
        );
    });

    // Gestion de la validation du formulaire
    // on récupère l'élement checkbox
    const checkbox = document.getElementById('commande_cacheplaque_validation');
    // on récupère le bouton de validation du formulaire "ajouter au panier"
    const submitBtn = document.getElementById('commande_cacheplaque_submit');

    // on écoute la checkbox et on change l'état du bonton "ajouter au panier" en fonction de l'état de la checkbox
    checkbox.addEventListener('change', function () {
        // le bouton submit est disabled quand la checkbox n'est pas cochée
        submitBtn.disabled = !this.checked;
    });
}

// Création dynamique du conteneur d’affichage
function createDisplayContainer(field, typeLabel) {
    // On crée un nouvel élément div pour afficher l'exemplaire sélectionné
    const container = document.createElement('div');

    // Style de base du conteneur
    container.style.border = '1px solid #ccc';
    container.style.padding = '1em';
    container.style.marginBottom = '1em';
    container.style.height = '110px';
    // Contenu par défaut si aucun exemplaire n'est sélectionné
    container.innerHTML = `<p style="margin: 0; color: #666;">Aucun exemplaire ${typeLabel} sélectionné.</p>`;

    // On insère le conteneur en haut du formulaire
    field.closest('form').prepend(container);
    // On retourne le conteneur
    return container;
}

// Gestion du clic sur un bouton de sélection
function handleChoixClick(button, exemplaireMap) {
    // On récupère la div contenant le bouton cliqué (et donc l'exemplaire associé)
    const exemplaireDiv = button.closest('.exemplaire');
     // On lit les données stockées dans les attributs data-* de la div
    const id = exemplaireDiv.dataset.id; // ID de l'exemplaire
    const produit = exemplaireDiv.dataset.produit; // Type de produit (2 ou 3)
 
    // On détermine la cible dans la map en fonction du type de produit
    const target = exemplaireMap[produit];
    // Si aucune cible trouvée, on arrête (sécurité)
    if (!target) return;

    // On met à jour la valeur du champ caché avec l'ID sélectionné
    target.field.value = id;
    // on active la checkbox du formulaire  
    document.getElementById('commande_cacheplaque_validation').disabled = false;

    // On récupère la zone contenant les informations d'affichage de l'exemplaire
    const info = exemplaireDiv.querySelector('.exemplaire-info');

    // On affiche les informations de l'exemplaire sélectionné dans le conteneur prévu
    target.display.innerHTML = info.outerHTML;
}














