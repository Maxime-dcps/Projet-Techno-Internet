<?php
if(!isset($_SESSION['admin'])){
    header("location:../../index_.php?page=accueil.php");
}
