<?php

function generateUuidV4(): string
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$id_oeuvre = null; // L'ID de l'oeuvre à modifier
$titre_val = $description_val = $artiste_val = $annee_creation_val = $dimensions_val = $prix_val = $statut_val = $id_type_val = "";
$image_current_path = ""; // Chemin de l'image actuelle de l'oeuvre pour affichage
$erreurs = [];
$types = [];

if(isset($_SESSION["utilisateur_id"])) {

    if (isset($cnx)) {
        $typeOeuvresDAO = new TypeOeuvreDAO($cnx);
        $oeuvreDAO = new OeuvreDAO($cnx);

        // Récupérer les types d'oeuvre pour la liste déroulante
        $types = $typeOeuvresDAO->getAllTypes();
    }
    else {
        $erreurs["general"] = "Connexion à la base impossible.";
    }

    // Traitement de l'ID de l'oeuvre (GET ou POST)

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id_oeuvre = (int)$_GET['id'];
    } elseif (isset($_POST['id_oeuvre']) && is_numeric($_POST['id_oeuvre'])) {
        $id_oeuvre = (int)$_POST['id_oeuvre'];
    }

    if (!$id_oeuvre) {
        header('Location: ./index_.php?page=page_404.php');
        exit;
    }

    $oeuvre_data = null;

    $oeuvre_data = $oeuvreDAO->getOeuvreById($id_oeuvre);

    if (!$oeuvre_data) {
        header('Location: ./index_.php?page=list_oeuvres.php&message=Oeuvre introuvable');
        exit;
    } else {
        $utilisateur_actuel_id = $_SESSION["utilisateur_id"];
        $utilisateur_actuel_role = $_SESSION["utilisateur_role"];

        $est_proprietaire = ($oeuvre_data->id_utilisateur == $utilisateur_actuel_id);
        $est_administrateur = ($utilisateur_actuel_role === "administrateur");

        if (!$est_proprietaire && !$est_administrateur) {
            header('Location: ./index_.php?page=page_404');
            exit;
        }
    }

    if ($oeuvre_data && empty($erreurs)) {

        if (isset($_POST["oeuvre_update"]))
        {
            $titre_val = trim(isset($_POST['titre']) ? $_POST['titre'] : '');
            $description_val = trim(isset($_POST['description']) ? $_POST['description'] : '');
            $artiste_val = trim(isset($_POST['artiste']) ? $_POST['artiste'] : '');
            $annee_creation_val = trim(isset($_POST['annee_creation']) ? $_POST['annee_creation'] : '');
            $dimensions_val = trim(isset($_POST['dimensions']) ? $_POST['dimensions'] : '');
            $prix_val = trim(isset($_POST['prix']) ? $_POST['prix'] : '');
            $id_type_val = isset($_POST['id_type_oeuvre']) ? $_POST['id_type_oeuvre'] : '';

            // Validation des champs
            if ($titre_val === '') $erreurs['titre'] = "Le titre est requis.";
            if ($description_val === '') $erreurs['description'] = "La description est requise.";
            if ($artiste_val === '') $erreurs['artiste'] = "Le nom de l'artiste est requis.";
            if (!ctype_digit($annee_creation_val) || (int)$annee_creation_val < 1000 || (int)$annee_creation_val > (int)date('Y')) $erreurs['annee_creation'] = "Année de création invalide.";
            if ($id_type_val === '') $erreurs['id_type_oeuvre'] = "Le type d'oeuvre est requis.";

            // Gestion de l'image
            $chemin_image_bd = $oeuvre_data->image_url; // Assurez-vous que la propriété est bien 'chemin_image'

            $nouveau_chemin_relatif_image = $chemin_image_bd; // Par défaut, on garde l'ancienne image

            if (isset($_FILES['image_oeuvre']) && $_FILES['image_oeuvre']['error'] === UPLOAD_ERR_OK)
            {
                // Une nouvelle image a été téléchargée, on la traite
                $image_tmp = $_FILES['image_oeuvre']['tmp_name'];
                $image_name = basename($_FILES['image_oeuvre']['name']);
                $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
                $max_file_size = 5 * 1024 * 1024; // 5 Mo en octets

                if ($_FILES['image_oeuvre']['size'] > $max_file_size) $erreurs['image'] = "La nouvelle image dépasse la taille maximale autorisée de 3 Mo.";
                elseif ($_FILES['image_oeuvre']['size'] === 0) $erreurs['image'] = "La nouvelle image est vide.";
                elseif (!in_array(strtolower($image_ext), $allowed_ext)) $erreurs['image'] = "Format d'image non autorisé (.jpg, .jpeg, .png, .webp).";

                if (empty($erreurs['image'])) {
                    $image_new_name = generateUuidV4() . '.' . $image_ext;
                    $dossier_cible = dirname(__DIR__) . '/admin/assets/images/';
                    $chemin_absolu_nouvelle_image = $dossier_cible . $image_new_name;

                    if (move_uploaded_file($image_tmp, $chemin_absolu_nouvelle_image)) {
                        $nouveau_chemin_relatif_image = 'images/' . $image_new_name;

                        // Si une ancienne image existait, la supprimer
                        if ($chemin_image_bd && file_exists($dossier_cible . basename($chemin_image_bd))) {
                            unlink($dossier_cible . basename($chemin_image_bd));
                        }
                    } else {
                        $erreurs['image'] = "Échec lors de l'envoi de la nouvelle image.";
                    }
                }
            }

            // Si aucune erreur de validation
            if (empty($erreurs)) {

                $annee_creation_val = $annee_creation_val === "" ? null : (int)$annee_creation_val;
                $dimensions_val = $dimensions_val === "" ? null : $dimensions_val;
                $prix_val = $prix_val === "" ? null : (float)$prix_val;

                // updateOeuvre($id, $titre, $description, $artiste, $id_type, $id_utilisateur, $annee_creation, $dimensions, $prix, $chemin_image)
                $update_result = $oeuvreDAO->updateOeuvre(
                    $id_oeuvre,
                    $titre_val,
                    $description_val,
                    $artiste_val,
                    $id_type_val,
                    $oeuvre_data->id_utilisateur,
                    $annee_creation_val,
                    $dimensions_val,
                    $prix_val,
                    $nouveau_chemin_relatif_image
                );

                if($update_result)
                {
                    // Redirection après succès
                    header('location: ./index_.php?page=detail_oeuvre.php&id=' . $id_oeuvre);
                    exit;
                }
                else {
                    $erreurs["general"] = "Une erreur s'est produite lors de la mise à jour de l'oeuvre en base de données.";
                }
            }
        }

        // --- PRÉ-REMPLISSAGE DU FORMULAIRE (GET ou après erreur POST) ---
        if (!isset($_POST["oeuvre_update"]) || !empty($erreurs)) {

            $titre_val = $titre_val ?: $oeuvre_data->titre;
            $description_val = $description_val ?: $oeuvre_data->description;
            $artiste_val = $artiste_val ?: $oeuvre_data->artiste;
            $annee_creation_val = $annee_creation_val ?: $oeuvre_data->annee_creation;
            $dimensions_val = $dimensions_val ?: $oeuvre_data->dimensions;
            $prix_val = $prix_val ?: $oeuvre_data->prix;
            $id_type_val = $id_type_val ?: $oeuvre_data->id_type_oeuvre;

            $image_current_path = './admin/assets/' . $oeuvre_data->image_url;
        }
    }

}
else {
    // Redirection si l'utilisateur n'est pas connecté
    $id_to_redirect = $id_oeuvre ?? (isset($_GET['id']) ? (int)$_GET['id'] : '');
    header('Location: ./index_.php?page=login.php&previous_page=detail_oeuvre.php&id=' . $id_to_redirect);
    exit;
}

$form_title = "Modifier l'annonce";
$submit_button_name = "oeuvre_update";
$submit_button_text = "Mettre à jour";
$is_update = true; // Indique au formulaire que nous sommes en mode modification

// Inclusion du formulaire HTML
include 'oeuvre_form.php';
?>