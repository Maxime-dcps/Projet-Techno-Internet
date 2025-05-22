<?php

$ableToAlter = false;

if (isset($cnx))
{
    $detailOeuvreDAO = new DetailOeuvreDAO($cnx);
    $oeuvreDAO = new OeuvreDAO($cnx);
}

if (isset($_GET['id'])) {
    $id_oeuvre = (int)$_GET['id'];
} else {
    header('Location: ./index_.php?page=page_404.php');
}

$oeuvre = $detailOeuvreDAO->getDetailOeuvresById($id_oeuvre);

if (!$oeuvre)
{
    header('Location: ./index_.php?page=page_404.php');
    exit;
}

if(isset($_SESSION["utilisateur_id"]))
{
    $ableToAlter = ($_SESSION["utilisateur_id"] == $oeuvre->id_utilisateur || $_SESSION["utilisateur_role"] == "administrateur");

    if($ableToAlter && isset($_POST['btn_supprimer']) && isset($_POST['oeuvre_id_to_delete']))
    {
        $id_to_delete = (int)$_POST['oeuvre_id_to_delete'];

        $resultat_suppression = $oeuvreDAO->supprimerOeuvre($id_to_delete);

        if($resultat_suppression === 1)
        {
            header('Location: ./index_.php?page=accueil.php');
            exit;
        }
        else{
            $error = "Impossible de supprimer cette annonce, veuillez ressayer plus tard.";
        }
    }
}

?>

<div class="container mt-4 mb-5">
    <div class="row g-4">
        <div class="col-lg-7">

            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger" role="alert">
                    <h5 class="alert-heading">Erreur lors de la suppression</h5>
                    <p><?= $erreur ?></p>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded overflow-hidden">
                <?php if (!empty(trim($oeuvre->image_url))): ?>
                    <img src="./admin/assets/<?= $oeuvre->image_url ?>" class="d-block w-100" alt="<?= $oeuvre->titre ?>" id="detail-image">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <p class="text-muted mt-3">Aucune image disponible pour cette Oeuvre</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="card-title h2 mb-1"><?= $oeuvre->titre ?></h1>
                            <p class="card-subtitle mb-2 text-muted"><em>Par <?= $oeuvre->artiste ?></em></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <?php if ($oeuvre->prix !== null): ?>
                            <span class="badge bg-primary rounded-pill fs-4 py-2 px-3" id="price-badge"><?= $oeuvre->prix ?> €</span>
                        <?php else: ?>
                            <span class="badge bg-primary rounded-pill fs-5 py-2 px-3">Prix sur demande</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">Description</h5>
                        <div class="description-box p-3 bg-light rounded">
                            <p><?= $oeuvre->description ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">Détails</h5>
                        <ul class="list-unstyled">
                            <?php if (!empty(trim($oeuvre->annee_creation))): ?>
                                <li><strong>Année de création :</strong> <?= $oeuvre->annee_creation ?></li>
                            <?php endif; ?>
                            <?php if (!empty(trim($oeuvre->dimensions))): ?>
                                <li><strong>Dimensions :</strong> <?= $oeuvre->dimensions ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="card bg-light border-0 rounded p-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="text-muted">Vendu par</span>
                                <h6 class="mb-0 fw-bold"><?= $oeuvre->nom_vendeur ?></h6>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto text-muted small pt-3 border-top">
                        <p class="mb-0">Publié le : <?= date('d/m/Y', strtotime($oeuvre->date_publication)) ?></p>
                    </div>

                    <div class="mt-3">
                        <a href="./index_.php?page=accueil.php" class="btn btn-primary w-100">
                            Retour au Catalogue
                        </a>
                    </div>

                    <?php if($ableToAlter): ?>
                        <div class="mt-3 d-flex gap-2 justify-content-around">

                            <a href="./index_.php?page=modifier_oeuvre.php&id=<?= $oeuvre->id_oeuvre ?>" class="btn btn-secondary">
                                Modifier l'annonce
                            </a>

                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette oeuvre ?');">
                                <input type="hidden" name="oeuvre_id_to_delete" value="<?= $oeuvre->id_oeuvre?>">
                                <button type="submit" class="btn btn-primary" name="btn_supprimer">Supprimer l'annonce</button>
                            </form>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>