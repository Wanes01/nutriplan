<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$params = [
    "title" => "Dieta | NutriPlan",
    "main" => "./main/diet-crud-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php",
    // "ingredients" => $dbh->getIngredients()
    // "js" => array("recipe-crud.js")
];

/*
if (isset($_GET["title"])) {
    $params["recipeData"] = $dbh->getRecipeData($_SESSION['nickname'], $_GET['title']);
}
*/

require_once "./base.php";
?>