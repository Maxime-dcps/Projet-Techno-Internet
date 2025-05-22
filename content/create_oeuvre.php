<?php

function generateUuidV4(): string
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


$titre_val = $description_val = $artiste_val = $annee_creation_val = $dimensions_val = $prix_val = $statut_val = $id_type_val = "";
$message_succes_creation = "";
$erreurs_creation = [];

if(isset($_SESSION["utilisateur_id"])) {

    if (isset($cnx)) {
        $typeOeuvresDAO = new TypeOeuvreDAO($cnx);
        $oeuvreDAO = new OeuvreDAO($cnx);

        $types = $typeOeuvresDAO->getAllTypes();
    }
    else $erreurs_creation["general"] = "Connexion à la base impossible.";

    if (isset($_POST["oeuvre_register"]))
    {
        $id_utilisateur_connecte = $_SESSION["utilisateur_id"];

        $titre_val = trim(isset($_POST['titre']) ? $_POST['titre'] : '');
        $description_val = trim(isset($_POST['description']) ? $_POST['description'] : '');
        $artiste_val = trim(isset($_POST['artiste']) ? $_POST['artiste'] : '');
        $annee_creation_val = trim(isset($_POST['annee_creation']) ? $_POST['annee_creation'] : '');
        $dimensions_val = trim(isset($_POST['dimensions']) ? $_POST['dimensions'] : '');
        $prix_val = trim(isset($_POST['prix']) ? $_POST['prix'] : '');
        $statut_val = isset($_POST['statut_oeuvre']) ? $_POST['statut_oeuvre'] : '';
        $id_type_val = isset($_POST['id_type_oeuvre']) ? $_POST['id_type_oeuvre'] : '';

        if ($titre_val === '') $erreurs_creation['titre'] = "Le titre est requis.";
        if ($description_val === '') $erreurs_creation['description'] = "La description est requise.";
        if ($artiste_val === '') $erreurs_creation['artiste'] = "Le nom de l'artiste est requis.";
        if (!ctype_digit($annee_creation_val) || (int)$annee_creation_val < 1000 || (int)$annee_creation_val > (int)date('Y')) $erreurs_creation['annee_creation'] = "Année de création invalide.";
        if ($id_type_val === '') $erreurs_creation['id_type_oeuvre'] = "Le type d'oeuvre est requis.";

        //Traitement images
        print (isset($_FILES['image_oeuvre']) && $_FILES['image_oeuvre']['error'] === UPLOAD_ERR_OK);
        print isset($_FILES['image_oeuvre']);
        print $_FILES['image_oeuvre']['error'] === UPLOAD_ERR_OK;
        if (isset($_FILES['image_oeuvre']) && $_FILES['image_oeuvre']['error'] === UPLOAD_ERR_OK)
        {
            $image_tmp = $_FILES['image_oeuvre']['tmp_name'];
            $image_name = basename($_FILES['image_oeuvre']['name']);
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

            $max_file_size = 3 * 1024 * 1024; // 3 Mo en octets

            if ($_FILES['image_oeuvre']['size'] > $max_file_size) $erreurs_creation['image'] = "L'image dépasse la taille maximale autorisée de 3 Mo.";
            elseif ($_FILES['image_oeuvre']['size'] === 0) $erreurs_creation['image'] = "L'image est vide.";
            elseif (!in_array(strtolower($image_ext), $allowed_ext)) $erreurs_creation['image'] = "Format d'image non autorisé (.jpg, .jpeg, .png, .webp).";

        } else {
            $erreurs_creation['image'] = "Une image conforme est requise.";
        }

        if (empty($erreurs_creation)) {

            $image_new_name = generateUuidV4() . '.' . $image_ext;
            $dossier_cible = dirname(__DIR__) . '/admin/assets/images/';
            $chemin_relatif = 'images/' . $image_new_name;
            $chemin_absolu = $dossier_cible . $image_new_name;

            if (move_uploaded_file($image_tmp, $chemin_absolu)) {

                $annee_creation_val = $annee_creation_val === "" ? null : $annee_creation_val;
                $dimensions_val = $dimensions_val === "" ? null : $dimensions_val;
                $prix_val = $prix_val === "" ? null : $prix_val;

                $idNewOeuvre = $oeuvreDAO->createOeuvre($titre_val, $description_val, $artiste_val, $id_type_val, $id_utilisateur_connecte, $annee_creation_val, $dimensions_val, $prix_val, $chemin_relatif);

                if($idNewOeuvre > 0)
                {
                    header('location: ./index_.php?page=detail_oeuvre.php&id=' . $idNewOeuvre);
                    exit;
                }
                else $erreurs_creation["general"] = "Une erreur s'est produite lors de l'enregistrement.";

            }
            else{
                $erreurs_creation['image'] = "Échec lors de l'envoi de l'image.";
            }
        }
    }
}
else{
    header('Location: ./index_.php?page=login.php&previous_page=create_oeuvre.php');
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="card-title text-center mb-4 fw-bold">Créer une oeuvre</h2>
                    <hr>

                    <?php if (!empty($erreurs_creation['general'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($erreurs_creation['general']) ?>
                        </div>
                    <?php endif; ?>

                    <form id="form-create-oeuvre" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control <?= !empty($erreurs_creation['titre']) ? 'is-invalid' : '' ?>" id="titre" name="titre" value="<?= htmlspecialchars($titre_val) ?>" placeholder="Titre">
                            <label for="titre">Titre</label>
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['titre']) ? $erreurs_creation['titre'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control <?= !empty($erreurs_creation['description']) ? 'is-invalid' : '' ?>" id="description" name="description" placeholder="Description" style="height: 100px;"><?= htmlspecialchars($description_val) ?></textarea>
                            <label for="description">Description</label>
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['description']) ? $erreurs_creation['description'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control <?= !empty($erreurs_creation['artiste']) ? 'is-invalid' : '' ?>" id="artiste" name="artiste" value="<?= htmlspecialchars($artiste_val) ?>" placeholder="Artiste">
                            <label for="artiste">Artiste</label>
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['artiste']) ? $erreurs_creation['artiste'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" min="1000" max="<?= date('Y') ?>" class="form-control <?= !empty($erreurs_creation['annee_creation']) ? 'is-invalid' : '' ?>" id="annee_creation" name="annee_creation" value="<?= htmlspecialchars($annee_creation_val) ?>" placeholder="Année de création">
                            <label for="annee_creation">Année de création</label>
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['annee_creation']) ? $erreurs_creation['annee_creation'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control <?= !empty($erreurs_creation['dimensions']) ? 'is-invalid' : '' ?>" id="dimensions" name="dimensions" value="<?= htmlspecialchars($dimensions_val) ?>" placeholder="Dimensions">
                            <label for="dimensions">Dimensions</label>
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['dimensions']) ? $erreurs_creation['dimensions'] : '' ?></div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control <?= !empty($erreurs_creation['prix']) ? 'is-invalid' : '' ?>" id="prix" name="prix" value="<?= htmlspecialchars($prix_val) ?>" placeholder="Prix">
                                <span class="input-group-text">€</span>
                                <div class="invalid-feedback"><?= !empty($erreurs_creation['prix']) ? $erreurs_creation['prix'] : '' ?></div>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="image_oeuvre" class="form-label">Photographie</label>
                            <input type="file" class="form-control <?= !empty($erreurs_creation['image']) ? 'is-invalid' : '' ?>" id="image_oeuvre" name="image_oeuvre" accept="image/*">
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['image']) ? $erreurs_creation['image'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select <?= !empty($erreurs_creation['id_type_oeuvre']) ? 'is-invalid' : '' ?>" id="id_type_oeuvre" name="id_type_oeuvre" aria-label="Type d'oeuvre" required>
                                <option value="" <?= $id_type_val === '' ? 'selected' : '' ?>>Choisir un type</option>
                                <?php if (!empty($types)): ?>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?= $type->id_type_oeuvre ?>" <?= ($id_type_val == $type->id_type_oeuvre) ? 'selected' : '' ?>>
                                            <?= $type->nom_type ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="id_type_oeuvre">Type d'oeuvre</label>
                            <div class="invalid-feedback"><?= !empty($erreurs_creation['id_type_oeuvre']) ? $erreurs_creation['id_type_oeuvre'] : '' ?></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" name="oeuvre_register">Créer l'oeuvre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./admin/assets/js/create_oeuvre.js"></script>
