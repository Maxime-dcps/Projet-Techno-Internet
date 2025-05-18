<?php

// Par défaut, si aucun titre particulier n'est défini --> titre générique
$title = "Templum Artis";

// Définition de la page à afficher et création de la variable de session
if(!isset($_SESSION['page']))
{
    $_SESSION['page'] = 'content/accueil.php';
}
if(isset($_GET['page']))
{
    $_SESSION['page'] = 'content/'.$_GET['page'];
}

// Gestion des balises SEO par page
//switch ($_SESSION['page']) {
//    case "pdo_db.php":
//        $title = "Exercices pdo | Site 2025";
//        // $canonical = "si nécessaire ... ";
//        break;
//}

// Vérifier si la page existe dans l'arborescence
if (!file_exists($_SESSION['page'])) {
    $title = "Page introuvable | Templum Artis";
    $_SESSION['page'] = 'content/page_404.php';
}
