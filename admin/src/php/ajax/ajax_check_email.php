<?php
header('Content-Type: application/json');
require '../db/db_pg_connect.php';
require '../classes/Connection.class.php';
require '../classes/Utilisateur.class.php';
require '../classes/UtilisateurDAO.class.php';
$cnx = Connection::getInstance($dsn,$user,$password);

$response = ['isValid' => true, 'message' => ''];

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);

    if (empty($email)) {
        $response['message'] = '';
        $response['isValid'] = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Format d\'email invalide.';
        $response['isValid'] = false;
    } else {
        try {
            $utilisateurDAO = new UtilisateurDAO($cnx);
            if ($utilisateurDAO->emailExiste($email)) {
                $response['isValid'] = false;
                $response['message'] = 'Cette adresse e-mail est déjà utilisée.';
            }
        } catch (Exception $e) {
            $response['message'] = 'Erreur serveur lors de la vérification.';
            $response['isValid'] = false;
        }
    }
} else {
    $response['message'] = 'Aucune adresse e-mail fournie.';
    $response['isValid'] = false;
}

echo json_encode($response);
?>