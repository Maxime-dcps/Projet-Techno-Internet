<?php
header('Content-Type: application/json');
require '../db/db_pg_connect.php';
require '../classes/Connection.class.php';
require '../classes/Oeuvre.class.php';
require '../classes/OeuvreDAO.class.php';

if (isset($dsn) && isset($user) && isset($password))
{
    $cnx = Connection::getInstance($dsn,$user,$password);

    $filtre_type = isset($_GET['filtre_type']) ? $_GET['filtre_type'] : null;
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'id_oeuvre';
    $tri_ordre = isset($_GET['tri_ordre']) ? $_GET['tri_ordre'] : 'ASC';


    $oeuvreDAO = new OeuvreDAO($cnx);

    $oeuvres = $oeuvreDAO->getAllOeuvres($filtre_type, $order_by, $tri_ordre, true);

    print json_encode($oeuvres);
}


