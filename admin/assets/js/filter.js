$(document).ready(function () {

    $('#filtre_type, #order_by, #tri_ordre').on('change', function () {
        const data = {};

        ['filtre_type', 'order_by', 'tri_ordre'].forEach(id => {
            const val = $(`#${id}`).val();
            if (val !== "") data[id] = val;
        });

        console.log(data)

        $.ajax({
            url: "./admin/src/php/ajax/ajax_get_oeuvres.php",
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (oeuvres) {
                let html = '';

                if (oeuvres.length === 0) {
                    html = `
            <div class="text-center py-5">
                <p class="lead">Aucune oeuvre ne correspond à vos critères de recherche actuels.</p>
                <p>
                    <a href="./index_.php?page=accueil.php" class="btn btn-outline-secondary mt-2">
                        Réinitialiser les filtres
                    </a>
                </p>
            </div>
        `;
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
                </div>
            `;
                    });
                }

                $('#liste-oeuvres').html(`<div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">${html}</div>`);
            }

        });
    });
});
