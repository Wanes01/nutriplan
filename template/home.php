<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$params = [
    "title" => "Home | NutriPlan",
    "main" => "./main/" . $_SESSION['role'] . "-home-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php",
];

if ($_SESSION['role'] === "amministratori") {
    $params["ingredienti"] = $dbh->getIngredients();
    $params["js"] = array(ROOT . "js/admin-home.js");
}

require_once "./base.php";
?>