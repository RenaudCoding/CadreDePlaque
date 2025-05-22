console.log("JS choix de l'exemplaire de barrette chargé !");

document.addEventListener('DOMContentLoaded', initChoixExemplaire);

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
}

// fonction de création du conteneur qui sera affiché
function createSelectedDisplayContainer(exemplaireField) {
    // on créer un conteneur contenant une div
    const container = document.createElement('div');
    // on insert ce conteneur au début du formulaire contenant le champ "exemplaireField"
    exemplaireField.closest('form').prepend(container);

    container.style.border = '1px solid #ccc';
    container.style.padding = '1em';
    container.style.marginBottom = '1em';
    container.style.height = '110px';
    container.innerHTML = '<p style="margin: 0; color: #666;">Aucun exemplaire sélectionné.</p>';

    // on retourne le conteneur pour qu'il soit visible
    return container;
}

// fonction qui défini les actions après un clic
function handleChoixClick(button, exemplaireField, selectedDisplay) {
    // on retrouve la div avec la class=exemplaire contenant le bouton sur lequel on a cliqué
    const exemplaireDiv = button.closest('.exemplaire');
    // on défini une constante "id" à laquelle on affecte la valeur indiquée dans l'attribut "data-id" de la div récupérée
    const id = exemplaireDiv.dataset.id;

    // on transmet l'id dans le champ exemplaireField du formulaire
    exemplaireField.value = id;

    // on récupère la div avec la class=exemplaire-info
    const info = exemplaireDiv.querySelector('.exemplaire-info');
    // on l'affiche dans le conteneur créer dans la fonction
    selectedDisplay.innerHTML = info.outerHTML;

    console.log('ID lu depuis data-id :', id);
}
