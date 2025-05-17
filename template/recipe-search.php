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

$recipes = $dbh->getRandomRecipes(DEFAULT_RECIPE_NUM);

require_once "./base.php";
?>