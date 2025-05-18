<?php
require_once "../bootstrap.php";

define("LOADED_RECIPES", 10);

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$params = [
    "title" => "Cerca ricette | NutriPlan",
    "main" => "./main/recipe-search-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php"
];

$recipes = isset($_POST['title'])
    ? $dbh->filterRecipes(
        $_POST['title'],
        $_POST['minKcals'],
        $_POST['maxKcals'],
        $_POST['minPrice'],
        $_POST['maxPrice'],
        isset($_POST['accredited']),
        $_POST['order'],
        LOADED_RECIPES
        )
    : $dbh->filterRecipes("", "", "", "", "", "", "random", LOADED_RECIPES);

require_once "./base.php";
?>