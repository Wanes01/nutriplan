<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$params = [
    "title" => "Home | NutriPlan",
    "main" => "./main/my-recipes-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php",
    "recipes" => $dbh->getUserRecipes($_SESSION["nickname"])
];

require_once "./base.php";
?>