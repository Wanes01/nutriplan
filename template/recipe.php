<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname']) || !isset($_GET['title'])) {
    header("Location: " . ROOT . "index.php");
}

$recipeData = $dbh->getRecipeData(rawurldecode($_GET['nickname']), rawurldecode($_GET['title']));
$recipe = $recipeData["recipe"];
$ingredients = $recipeData["ingredients"];
unset($recipeData);

$params = [
    "title" => $recipe['titolo'] . " [" . $recipe['nicknameEditore'] . "] | NutriPlan",
    "main" => "./main/recipe-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php"
];

require_once "./base.php";
?>