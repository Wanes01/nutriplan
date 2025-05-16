<?php
require_once "../bootstrap.php";

try {
    $dbh->login($_POST["nickname"], $_POST["password"]);
    // login riuscito, reindirizzamento alla home page
    header("Location: " . ROOT . "template/home.php");
} catch (Exception $e) {
    $_SESSION["loginError"] = $e->getMessage();
    // login fallito, ricaricamento del login form con l'errore in evidenza
    header("Location: " . ROOT . "template/user-login.php");
}



?>