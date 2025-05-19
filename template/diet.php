<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$recipes = $dbh->getDietRecipes(rawurldecode($_GET['name']), $_SESSION['nickname']);
$diet = $dbh->getDietaData($_SESSION['nickname'], rawurldecode($_GET['name']));

$params = [
    "title" => $diet['nome'] . " | NutriPlan",
    "main" => "./main/diet-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php"
];

require_once "./base.php";
?>