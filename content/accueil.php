<?php
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'o.id_oeuvre';
$order_direction = isset($_GET['tri_ordre']) ? $_GET['tri_ordre'] :'ASC';
$search_term = isset($_GET['central_search']) ? trim($_GET['central_search']) : null;
$filtre_type_id = (isset($_GET['filtre_type']) && $_GET['filtre_type'] !== '') ? (int)$_GET['filtre_type'] : null;

if (isset($cnx)) {
    $oeuvreDAO = new OeuvreDAO($cnx);
    $typeOeuvresDAO = new TypeOeuvreDAO($cnx);

    $types = $typeOeuvresDAO->getAllTypes();
    $oeuvres = $oeuvreDAO->getAllOeuvres($filtre_type_id, $order_by, $order_direction, $search_term);
}

?>

<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="page" value="accueil.php">

    <div class="text-center mb-5 pt-4 pb-3 bg-light rounded-3 shadow-sm" id="desc-div">
        <h1 class="display-4">L’art s’expose, l’émotion s’invite</h1>
        <p class="lead text-muted">Explorez notre collection et laissez-vous guider par votre curiosité.</p>

        <!-- Barre de recherche centrale -->
        <div class="mt-4 mb-4 d-flex justify-content-center">
            <div class="input-group input-group-lg w-75 mt-4" id="big-search">
                <input
                        type="text"
                        id="central_search"
                        name="central_search"
                        class="form-control"
                        placeholder="Rechercher par titre, artiste ou description"
                        value="<?= htmlspecialchars($search_term ?? '') ?>"
                        aria-label="Recherche d'oeuvres"
                >
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Colonne filtres -->
            <div class="col-md-3 mb-4">
                <div class="position-sticky">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0">Filtrer et Trier</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label for="filtre_type" class="form-label fw-semibold">Type d'oeuvre :</label>
                                    <select name="filtre_type" id="filtre_type" class="form-select form-select-sm">
                                        <option value="">Tous les types</option>
                                        <?php if (!empty($types)): ?>
                                            <?php foreach ($types as $type): ?>
                                                <option value="<?= $type->id_type_oeuvre ?>" <?= ($filtre_type_id == $type->id_type_oeuvre) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($type->nom_type) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="order_by" class="form-label fw-semibold">Trier par :</label>
                                    <select name="order_by" id="order_by" class="form-select form-select-sm">
                                        <option value="id_oeuvre" <?= ($order_by == 'id_oeuvre') ? 'selected' : '' ?>>Par défaut</option>
                                        <option value="date_publication" <?= ($order_by == 'date_publication') ? 'selected' : '' ?>>Date</option>
                                        <option value="prix" <?= ($order_by == 'prix') ? 'selected' : '' ?>>Prix</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="tri_ordre" class="form-label fw-semibold">Ordre :</label>
                                    <select name="tri_ordre" id="tri_ordre" class="form-select form-select-sm">
                                        <option value="ASC" <?= ($order_direction == 'ASC') ? 'selected' : '' ?>>Croissant</option>
                                        <option value="DESC" <?= ($order_direction == 'DESC') ? 'selected' : '' ?>>Décroissant</option>
                                    </select>
                                </div>

                                <div class="col-12 d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm">Appliquer les filtres</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne oeuvres -->
            <div class="col-md-9" id="liste-oeuvres">
                <?php if (!empty($oeuvres)): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4">
                        <?php foreach ($oeuvres as $oeuvre): ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <a href="./index_.php?page=detail_oeuvre.php&id=<?= $oeuvre->id_oeuvre ?>">
                                        <?php if (!empty(trim($oeuvre->image_url))): ?>
                                            <img src="./admin/assets/<?= htmlspecialchars($oeuvre->image_url) ?>" class="card-img-top" alt="<?= htmlspecialchars($oeuvre->titre) ?>">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center card-img-top text-muted bg-light">
                                                <span>Image non disponible</span>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">
                                            <a href="./index_.php?page=detail_oeuvre.php&id=<?= $oeuvre->id_oeuvre ?>" class="text-decoration-none"><?= htmlspecialchars($oeuvre->titre) ?></a>
                                        </h5>
                                        <p class="card-text text-muted mb-2"><em>Par <?= htmlspecialchars($oeuvre->artiste) ?></em></p>

                                        <?php if (!empty(trim($oeuvre->prix)) && $oeuvre->prix !== null): ?>
                                            <p class="card-text fs-5 fw-bold mt-auto text-end"><?= htmlspecialchars($oeuvre->prix);?> €</p>
                                        <?php else: ?>
                                            <p class="card-text fs-5 fw-bold mt-auto text-end">Négociable</p>
                                        <?php endif; ?>

                                        <a href="./index_.php?page=detail_oeuvre.php&id=<?= $oeuvre->id_oeuvre ?>" class="btn btn-primary mt-2">Voir détails</a>
                                        <div class="text-muted small mt-2">
                                            Publié le: <?= date('d/m/Y', strtotime($oeuvre->date_publication)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column justify-content-center align-items-center w-100" id="no-card">
                        <p class="lead text-center">Aucune oeuvre ne correspond à vos critères de recherche actuels.</p>
                        <a href="./index_.php?page=accueil.php" class="btn btn-secondary mt-2">Réinitialiser les filtres</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<script src="./admin/assets/js/filter.js"></script>