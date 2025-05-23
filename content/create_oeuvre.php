<?php

function generateUuidV4(): string
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


$titre_val = $description_val = $artiste_val = $annee_creation_val = $dimensions_val = $prix_val = $statut_val = $id_type_val = "";
$erreurs = [];
$types = [];

if(isset($_SESSION["utilisateur_id"])) {

    if (isset($cnx)) {
        $typeOeuvresDAO = new TypeOeuvreDAO($cnx);
        $oeuvreDAO = new OeuvreDAO($cnx);

        $types = $typeOeuvresDAO->getAllTypes();
    }
    else {
        $erreurs["general"] = "Connexion à la base impossible.";
    }

    if (isset($_POST["oeuvre_create"]))
    {
        $id_utilisateur_connecte = $_SESSION["utilisateur_id"];

        $titre_val = trim(isset($_POST['titre']) ? $_POST['titre'] : '');
        $description_val = trim(isset($_POST['description']) ? $_POST['description'] : '');
        $artiste_val = trim(isset($_POST['artiste']) ? $_POST['artiste'] : '');
        $annee_creation_val = trim(isset($_POST['annee_creation']) ? $_POST['annee_creation'] : '');
        $dimensions_val = trim(isset($_POST['dimensions']) ? $_POST['dimensions'] : '');
        $prix_val = trim(isset($_POST['prix']) ? $_POST['prix'] : '');
        $id_type_val = isset($_POST['id_type_oeuvre']) ? $_POST['id_type_oeuvre'] : '';

        if ($titre_val === '') $erreurs['titre'] = "Le titre est requis.";
        if ($description_val === '') $erreurs['description'] = "La description est requise.";
        if ($artiste_val === '') $erreurs['artiste'] = "Le nom de l'artiste est requis.";
        if (!ctype_digit($annee_creation_val) || (int)$annee_creation_val < 1000 || (int)$annee_creation_val > (int)date('Y')) $erreurs['annee_creation'] = "Année de création invalide.";
        if ($id_type_val === '') $erreurs['id_type_oeuvre'] = "Le type d'oeuvre est requis.";

        // Traitement et validation de l'image
        if (isset($_FILES['image_oeuvre']) && $_FILES['image_oeuvre']['error'] === UPLOAD_ERR_OK)
        {
            $image_tmp = $_FILES['image_oeuvre']['tmp_name'];
            $image_name = basename($_FILES['image_oeuvre']['name']);
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

            $max_file_size = 5 * 1024 * 1024; // 5 Mo en octets

            if ($_FILES['image_oeuvre']['size'] > $max_file_size) $erreurs['image'] = "L'image dépasse la taille maximale autorisée de 3 Mo.";
            elseif ($_FILES['image_oeuvre']['size'] === 0) $erreurs['image'] = "L'image est vide.";
            elseif (!in_array(strtolower($image_ext), $allowed_ext)) $erreurs['image'] = "Format d'image non autorisé (.jpg, .jpeg, .png, .webp).";

        } else {
            $erreurs['image'] = "Une image conforme est requise.";
        }

        // Si aucune erreur de validation
        if (empty($erreurs)) {

            $image_new_name = generateUuidV4() . '.' . $image_ext;
            $dossier_cible = dirname(__DIR__) . '/admin/assets/images/';
            $chemin_relatif = 'images/' . $image_new_name;
            $chemin_absolu = $dossier_cible . $image_new_name;

            if (move_uploaded_file($image_tmp, $chemin_absolu)) {

                $annee_creation_val = $annee_creation_val === "" ? null : (int)$annee_creation_val;
                $dimensions_val = $dimensions_val === "" ? null : $dimensions_val;
                $prix_val = $prix_val === "" ? null : (float)$prix_val;

                // Création de l'oeuvre en base de données
                $idNewOeuvre = $oeuvreDAO->createOeuvre($titre_val, $description_val, $artiste_val, $id_type_val, $id_utilisateur_connecte, $annee_creation_val, $dimensions_val, $prix_val, $chemin_relatif);

                if($idNewOeuvre > 0)
                {
                    header('location: ./index_.php?page=detail_oeuvre.php&id=' . $idNewOeuvre);
                    exit;
                }
                else {
                    $erreurs["general"] = "Une erreur s'est produite lors de l'enregistrement de l'oeuvre en base de données.";

                    // Supprimer l'image uploadée si l'enregistrement DB échoue
                    if (file_exists($chemin_absolu)) {
                        unlink($chemin_absolu);
                    }
                }
            }
            else{
                $erreurs['image'] = "Échec lors de l'envoi du fichier image sur le serveur.";
            }
        }
    }
}
else {
    header('Location: ./index_.php?page=login.php&previous_page=create_oeuvre.php');
    exit;
}

// Définition des variables pour oeuvre_form.php

$form_title = "Ajouter une oeuvre";
$submit_button_name = "oeuvre_create";
$submit_button_text = "Créer l'annonce";
$is_update = false; // Indique que nous ne sommes pas en mode modification

// Inclusion du formulaire HTML
include 'oeuvre_form.php';
?>

<!--<script src="./admin/assets/js/create_oeuvre.js"></script>-->