console.log("JS calcul du prix chargé !");

// variable qui contiendra les grilles de tarifs
let tarifsByProduitId = {};

// chargement des grilles de tarifs
export function chargerTarifsGlobaux() {
    // requête GET vers la route qui renvoie les grilles de tarifs
    return fetch('/get-all-tarifs')
        // on transforme la réponse en objet JSON
        .then(res => res.json())
        // on récupère les grilles
        .then(data => {
            tarifsByProduitId = data.tarifs || {};
        })
        // si la requête échoue (ex: serveur indisponible)
        .catch(err => {
            console.error('Erreur lors du chargement des tarifs', err);
        });
}

// récupération du prix unitaire en fonction du produit et de la quantité
export function getPrixUnitaire(produitId, quantite) {
    // on récupère la grille de tarifs du produit demandé
    const grille = tarifsByProduitId[produitId];

    // si aucune grille trouvée pour ce produit on retourne 0
    if (!grille) return 0;

    // pour chaque élément de la grille (classée par ordre décroissant de seuil de quantité)
    for (let tarif of grille) {
        // on récupère le tarif ou la quantité demandée >= au seuil de quantité
        // exemple pour une quantité de 150 :
        // 150 < au seuil 200, mais 150 >= au seuil 100
        // le tarif sélectionné sera celui appliqué à partir du seuil de quantité 100
        if (quantite >= tarif.seuil) {
            return tarif.prix;
        }
    }

    // si aucun seuil n’est atteint (ex: quantite = 0), on retourne 0
    return 0;
}
