<?php
header('Content-Type: application/json');
require '../db/db_pg_connect.php';
require '../classes/Connection.class.php';
require '../classes/Oeuvre.class.php';
require '../classes/OeuvreDAO.class.php';

if (isset($dsn) && isset($user) && isset($password))
{
    $cnx = Connection::getInstance($dsn,$user,$password);

    $searchTerm = $_GET['searchTerm'] ?? '';
    if(strlen($searchTerm) > 1)
    {
        $oeuvreDAO = new OeuvreDAO($cnx);

        $suggestions = $oeuvreDAO->getOeuvreSuggestions($searchTerm);
        print json_encode($suggestions);
    }
    else print json_encode([]);
}