function debounce(func, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, arguments), delay);
    };
}

function fetchFilteredOeuvres() {
    const data = {};

    ['filtre_type', 'order_by', 'tri_ordre', 'central_search'].forEach(id => {
        const val = $(`#${id}`).val();
        if (val !== "") data[id] = val;
    });

    $.ajax({
        url: "./admin/src/php/ajax/ajax_get_oeuvres.php",
        method: 'GET',
        data: data,
        dataType: 'json',
        success: function (oeuvres) {
            let html = '';

            if (oeuvres.length === 0) {
                html = `
                    <div class="d-flex flex-column justify-content-center align-items-center w-100" id="no-card">
                        <p class="lead text-center">Aucune oeuvre ne correspond à vos critères de recherche actuels.</p>
                        <a href="./index_.php?page=accueil.php" class="btn btn-secondary mt-2">Réinitialiser les filtres</a>
                    </div>`;

            } else {
                oeuvres.forEach(oeuvre => {
                    html += `
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <a href="./index_.php?page=detail_oeuvre.php&id=${oeuvre['id_oeuvre']}">
                                    ${oeuvre['image_url'] ? `<img src="./admin/assets/${oeuvre['image_url']}" class="card-img-top" alt="${oeuvre['titre']}">` : `<div class="card-img-top text-center text-muted py-4">Image non disponible</div>`}
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="./index_.php?page=detail_oeuvre.php&id=${oeuvre['id_oeuvre']}" class="text-decoration-none">${oeuvre['titre']}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-2"><em>Par ${oeuvre['artiste']}</em></p>
                                    <p class="card-text fs-5 fw-bold mt-auto text-end">${oeuvre['prix'] ? oeuvre['prix'] + ' €' : 'Négociable'}</p>
                                    <a href="./index_.php?page=detail_oeuvre.php&id=${oeuvre['id_oeuvre']}" class="btn btn-primary mt-2">Voir détails</a>
                                    <div class="text-muted small mt-2">Publié le: ${new Date(oeuvre['date_publication']).toLocaleDateString('fr-FR')}</div>
                                </div>
                            </div>
                        </div>`;
                });
            }
            console.log("Avant update HTML");
            $('#liste-oeuvres').html(`<div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">${html}</div>`);
            console.log("Après update HTML");
        }
    });
}

// Déclarons une fonction debounce pour les événements d'autocomplétion
// et une autre pour la recherche principale, ou réutilisons la même.
// Pour la recherche principale, on veut qu'elle se déclenche une fois que l'utilisateur a *fini* de taper.
const debouncedFetchFilteredOeuvres = debounce(fetchFilteredOeuvres, 400);


$(document).ready(function () {
    $('#filtre_type, #order_by, #tri_ordre').on('change', fetchFilteredOeuvres);

    $("#central_search").autocomplete({
        source: function(request, response) {
            const data = {};

            data["searchTerm"] = request.term;

            $.ajax({
                url: "./admin/src/php/ajax/ajax_get_suggestions.php",
                dataType: "json",
                data: data,
                success: function(data) {
                    console.log("Réponse du serveur (suggestions) :", data);
                    response(data);
                },
                error: function() {
                    response([]);
                }
            });
        },
        minLength: 2,
        delay: 200,
        // Événement déclenché quand une suggestion est sélectionnée
        select: function(event, ui) {
            // 'ui.item.value' contient la valeur de la suggestion sélectionnée.
            $(this).val(ui.item.value);
            // Déclenche la recherche principale immédiatement après une sélection
            fetchFilteredOeuvres();
            return false; // Empêche le comportement par défaut de l'autocomplete (qui est de remplir l'input avec la suggestion et de faire un change)
        },
        // Événement déclenché quand l'autocomplete est fermé.
        // C'est un bon endroit pour déclencher la recherche si l'utilisateur a tapé mais n'a rien sélectionné.
        close: function(event, ui) {
             // Vérifier si la valeur du champ a changé depuis la dernière sélection/recherche
            debouncedFetchFilteredOeuvres();
        },

    });

    $("#central_search").on("blur", function(e) {
        // Optionnel: On peut utiliser une petite temporisation pour s'assurer que
        // le "select" ou "close" de l'autocomplete a eu le temps de se déclencher.
        // Sinon, le blur pourrait se déclencher avant le close/select.
        setTimeout(() => {
            if (!$(this).is(":focus") && $(this).val().length >= $(this).autocomplete("option", "minLength")) {
                 debouncedFetchFilteredOeuvres();
            }
        }, 100); // Petite temporisation
        console.log("Champ", e.type);
    });

    $("#central_search").on("focus", function(e) {
        console.log("Champ", e.type);
    });

    $("#central_search").on("keydown", function() {
        console.log("Touche tapée");
    });
});