// on importe le fichier CSS pour la page de commande de barrette
import '../styles/creation.css';

console.log('JS création exemplaire chargé !');

// on récupère et on écoute les boutons d'ajout de formulaire de décoration et de marquage
const addLogoBtn = document.getElementById('add-decoration');
const addTexteBtn = document.getElementById('add-marquage');

addLogoBtn.addEventListener('click', addFormDecoration);
addTexteBtn.addEventListener('click', addFormMarquage);


// fonction d'ajout d'un formulaire de décoration
function addFormDecoration() {

    // on récupère la div contenant les données du prototype du formulaire et la valeur de l'index
    const formElement = document.getElementById('exemplaire_decorations');
    const prototype = formElement.dataset.prototype;
    // on récupèrel'index qui nous servira pour modifier le __name__ du nouveau prototype
    let index = formElement.dataset.index;
    // on remplace __name__ par l'index ce qui permet d'identifier clairement de nouveau formulaire
    const newFormDecoration = prototype.replace(/__name__/g, index);

    // on créé une div à laquelle on attribut un class
    const newFormDecorationEl = document.createElement('div');
    newFormDecorationEl.classList.add('deco-form');
    // on y met le nouveau formulaire
    newFormDecorationEl.innerHTML = newFormDecoration;
    // on ajoute un bouton pour retirer le formulaire
    addDeleteButton(newFormDecorationEl);


    // on affiche le nouveau formulaire à la suite du dernier
    formElement.appendChild(newFormDecorationEl);
    // on incrémente l'index pour le prochain formulaire
    index++;
    // on injecte l'index du prochain formulaire dans la div contenant le dataset-index 
    formElement.dataset.index = index;

    
}

// fonction d'ajout d'un formulaire de marquage
function addFormMarquage() {
    
    // on récupère la div contenant les données du prototype du formulaire
    const formElement = document.getElementById('exemplaire_marquages');
    const prototype = formElement.dataset.prototype;
    // on compte les divs enfants ce qui nous servira pour modifier le __name__ du nouveau prototype
    let index = formElement.dataset.index;
    // on remplace __name__ par l'index ce qui permet d'identifier clairement de nouveau formulaire
    const newFormMarquage = prototype.replace(/__name__/g, index);

    // on créé une div à laquelle on attribut un class
    const newFormMarquageEl = document.createElement('div');
    newFormMarquageEl.classList.add('marquage-form');
    // on  met le nouveau formulaire
    newFormMarquageEl.innerHTML = newFormMarquage;
    // on ajoute un bouton pour retirer le formulaire
    addDeleteButton(newFormMarquageEl);


    // on affiche le nouveau formulaire à la suite du dernier
    formElement.appendChild(newFormMarquageEl);
    // on incrémente l'index pour le prochain formulaire
    index++;
     // on injecte l'index du prochain formulaire dans la div contenant le dataset-index 
    formElement.dataset.index = index;
}





// fonction d'ajout d'un bouton pour retirer un formulaire
    function addDeleteButton(element) {

        // on crée le bouton pour retirer un formulaire
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.classList.add('remove-form');
        deleteBtn.textContent = 'Supprimer';
        // on écoute le bouton pour retirer le formulaire lors du clique 
        deleteBtn.addEventListener('click', () => {
            // on retire le formulaire
            element.remove();
        });
        // on place le bouton a la fin du formulaire
        element.appendChild(deleteBtn);
    }
