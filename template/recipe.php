<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname']) || !isset($_GET['title'])) {
    header("Location: " . ROOT . "index.php");
}

$editorNickname = rawurldecode($_GET['nickname']);
$title = rawurldecode($_GET['title']);

$recipeData = $dbh->getRecipeData($editorNickname, $title);
$recipe = $recipeData["recipe"];
$ingredients = $recipeData["ingredients"];
unset($recipeData);

$params = [
    "title" => $recipe['titolo'] . " [" . $recipe['nicknameEditore'] . "] | NutriPlan",
    "main" => "./main/recipe-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php",
    "js" => array("recipe.js")
];

$comments = $dbh->getRecipeComments($editorNickname, $title);

if ($_SESSION['role'] == 'utenti') {
    $diets = $dbh->getUserDietsOnRecipeNotIncluded($title, $editorNickname, $_SESSION['nickname']);
}

require_once "./base.php";
?>