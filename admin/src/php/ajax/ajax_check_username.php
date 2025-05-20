<?php
header('Content-Type: application/json');
require '../db/db_pg_connect.php';
require '../classes/Connection.class.php';
require '../classes/Utilisateur.class.php';
require '../classes/UtilisateurDAO.class.php';
$cnx = Connection::getInstance($dsn,$user,$password);

$response = ['isValid' => true, 'message' => ''];

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    if (empty($username)) {
        $response['message'] = '';
        $response['isValid'] = true;
    } elseif (strlen($username) < 4) {
        $response['isValid'] = false;
        $response['message'] = 'Minimum 4 caractères.';
    } elseif (strlen($username) > 24) {
        $response['isValid'] = false;
        $response['message'] = 'Maximum 24 caractères.';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $response['isValid'] = false;
        $response['message'] = 'Caractères autorisés : lettres, chiffres, underscore.';
    } else {
        try {
            $utilisateurDAO = new UtilisateurDAO($cnx);
            if ($utilisateurDAO->pseudoExiste($username)) {
                $response['isValid'] = false;
                $response['message'] = 'Ce nom d\'utilisateur est déjà pris.';
            }
        } catch (Exception $e) {
            $response['isValid'] = false;
            $response['message'] = 'Erreur serveur lors de la vérification.';
        }
    }
} else {
    $response['message'] = 'Aucun nom d\'utilisateur fourni.';
    $response['isValid'] = false;
}

echo json_encode($response);
?>