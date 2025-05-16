<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$params = [
    "title" => "Ricetta | NutriPlan",
    "main" => "./main/recipe-crud-main.php",
    "header" => "./header/" . $_SESSION['role'] . "-header.php",
    "footer" => "./footer/generic-footer.php",
    "ingredients" => $dbh->getIngredients(),
    "js" => array("recipe-crud.js")
];

//$recipe = $dbh->getRecipeData($nickname, $)

require_once "./base.php";
?>