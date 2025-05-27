
// Script JavaScript unique pour barrette et cache plaque

// Quand la page est complètement chargée, on initialise le comportement
document.addEventListener('DOMContentLoaded', initChoixExemplaires);

// Fonction principale appelée au chargement de la page
function initChoixExemplaires() {
    // On récupère tous les boutons de sélection d'exemplaire
    const buttons = document.querySelectorAll('.choisir-exemplaire');

    // Configuration des différents types d'exemplaires à gérer
    const config = [
        {
            fieldId: 'commande_cacheplaque_exemplaireAvant', // ID du champ dans le formulaire
            typeLabel: 'avant',                               // Texte à afficher ("avant")
            produitId: '2'                                    // Valeur de data-produit correspondante
        },
        {
            fieldId: 'commande_cacheplaque_exemplaireArriere',
            typeLabel: 'arrière',
            produitId: '3'
        },
        {
            fieldId: 'commande_barrette_exemplaire',
            typeLabel: '',                                    // Pas de label spécifique
            produitId: null                                   // Pas de data-produit nécessaire
        }
    ];

    // Objet dans lequel on stockera les champs et conteneurs associés
    const exemplaires = {};

    // On parcourt la configuration pour créer dynamiquement les conteneurs
    config.forEach(({ fieldId, typeLabel, produitId }) => {
        const field = document.querySelector(`#${fieldId}`);  // On récupère le champ dans le DOM
        if (!field) return;                                   // Si le champ n'existe pas, on passe au suivant

        const display = createDisplayContainer(field, typeLabel); // On crée le conteneur d'affichage
        exemplaires[produitId ?? 'barrette'] = { field, display }; // On le stocke avec une clé : produitId ou 'barrette'
    });

    // Pour chaque bouton de sélection d'exemplaire
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const exemplaireDiv = button.closest('.exemplaire');  // On récupère la div englobante de l'exemplaire
            const id = exemplaireDiv.dataset.id;                  // On lit l'ID de l'exemplaire
            const produit = exemplaireDiv.dataset.produit;        // On lit le type de produit (2, 3 ou null)
            const info = exemplaireDiv.querySelector('.exemplaire-info'); // On récupère les infos à afficher

            let target;

            // On choisit la bonne cible (avant/arrière/barrette) en fonction du produit
            if (produit && exemplaires[produit]) {
                target = exemplaires[produit];
            } else if (exemplaires['barrette']) {
                target = exemplaires['barrette'];
            }

            if (!target) return;  // Si aucune cible ne correspond, on arrête

            target.field.value = id;                 // On met l'ID de l'exemplaire dans le champ caché
            target.display.innerHTML = info.outerHTML; // Et on affiche les infos dans le conteneur
        });
    });
}

// Fonction pour créer dynamiquement le conteneur d’affichage
function createDisplayContainer(field, typeLabel) {
    const container = document.createElement('div');     // On crée une nouvelle div pour l'affichage
    container.classList.add('exemplaire-display');       // On ajoute une classe pour la cibler plus tard

    // On applique un peu de style pour l'apparence
    container.style.border = '1px solid #ccc';
    container.style.padding = '1em';
    container.style.marginBottom = '1em';
    container.style.height = '110px';

    // On affiche un message par défaut si aucun exemplaire n'est sélectionné
    container.innerHTML = `<p style="margin: 0; color: #666;">Aucun exemplaire ${typeLabel || ''} sélectionné.</p>`;

    // On essaye de trouver le champ de quantité correspondant
    const quantiteFieldId = field.id.replace('exemplaire', 'quantite'); // On construit l'ID du champ quantité
    const quantiteInput = document.querySelector(`#${quantiteFieldId}`); // On récupère le champ quantité
    const fieldGroup = quantiteInput ? quantiteInput.closest('div') : null; // On récupère le conteneur du champ (avec label + input)

    // Si on trouve le groupe du champ quantité
    if (fieldGroup && fieldGroup.parentElement) {
        const parent = fieldGroup.parentElement;

        const existingDisplays = parent.querySelectorAll('.exemplaire-display'); // On vérifie s'il y a déjà une autre div d'affichage

        // Si c’est le conteneur "avant", on l’insère avant toute autre div
        if (typeLabel === 'avant' && existingDisplays.length > 0) {
            parent.insertBefore(container, existingDisplays[0]);
        } else {
            // Sinon on l’insère juste avant le champ quantité
            parent.insertBefore(container, fieldGroup);
        }
    } else {
        // Si on ne trouve pas de champ quantité, on insère à la fin du formulaire
        field.closest('form').append(container);
    }

    return container; // On retourne le conteneur pour pouvoir l'utiliser dans le script
}
