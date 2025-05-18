<?php
require_once "../bootstrap.php";

define("DEFAULT_RECIPE_NUM", 10);

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
        DEFAULT_RECIPE_NUM
        )
    : $dbh->filterRecipes("", "", "", "", "", "", "random", DEFAULT_RECIPE_NUM);

require_once "./base.php";
?>