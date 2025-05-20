<?php

if (isset($cnx))
{
    $oeuvreDAO = new OeuvreDAO($cnx);
    $oeuvres = $oeuvreDAO->getAllOeuvres();
}


?>

<div class="text-center mb-5 pt-4 pb-3 bg-light rounded-3 shadow-sm" id="desc-div">
    <h1 class="display-4">Notre Collection d'Oeuvres d'Art</h1>
    <p class="lead text-muted">Découvrez une sélection d'oeuvres d'artistes talentueux.</p>
</div>

<div class="container">
    <?php if (!empty($oeuvres)): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($oeuvres as $oeuvre):?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <a href="./index_.php?page=detail_oeuvre.php&id=<?= $oeuvre->id_oeuvre ?>">
                            <?php if (!empty(trim($oeuvre->image_url))): ?>
                                <img src="./admin/assets/<?= $oeuvre->image_url ?>" class="card-img-top" alt="<?= $oeuvre->titre ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center card-img-top">
                                    <span class="text-muted">Image non disponible</span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="./index_.php?page=detail_oeuvre.php&id=<?= $oeuvre->id_oeuvre ?>" class="text-decoration-none"><?= $oeuvre->titre ?></a>
                            </h5>
                            <p class="card-text text-muted mb-2"><em>Par <?= $oeuvre->artiste ?></em></p>

                            <?php if (!empty(trim($oeuvre->prix)) && $oeuvre->prix !== null): ?>
                                <p class="card-text fs-5 fw-bold mt-auto text-end"><?= $oeuvre->prix;?> €</p>
                            <?php else: ?>
                                <p class="card-text fs-5 fw-bold mt-auto text-end">Négociable</p>
                            <?php endif; ?>

                            <a href="./index_.php?page=detail_oeuvre.php&id=<?= htmlspecialchars($oeuvre->id_oeuvre) ?>" class="btn btn-primary mt-2">Voir détails</a>
                            <div class="text-muted small mt-2">
                                Publié le: <?= date('d/m/Y', strtotime($oeuvre->date_publication)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <p class="lead">Aucune oeuvre n'est actuellement disponible dans notre catalogue.</p>
            <p>Revenez bientôt !</p>
        </div>
    <?php endif; ?>
</div>