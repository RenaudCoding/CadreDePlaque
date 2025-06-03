
// on importe le fichier CSS pour la page panier
import '../styles/panier.css';

// Quand la page est complètement chargée, on lance l'initialisation
document.addEventListener('DOMContentLoaded', initPage);

function initPage () {
    setupQuantityInputs();
}

function setupQuantityInputs(containerSelector = '.quantite') {
    document.querySelectorAll(containerSelector).forEach(wrapper => {
        const input = wrapper.querySelector('.quantite-input');
        const btnIncrease = wrapper.querySelector('.btn-plus');
        const btnDecrease = wrapper.querySelector('.btn-moins');

        if (!input || !btnIncrease || !btnDecrease) return;

        btnIncrease.addEventListener('click', () => {
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('change'));
        });

        btnDecrease.addEventListener('click', () => {
            const newValue = parseInt(input.value) - 1;
            if (newValue >= parseInt(input.min || 1)) {
                input.value = newValue;
                input.dispatchEvent(new Event('change'));
            }
        });
    });
}