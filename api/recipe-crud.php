<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

$lines = explode("\n", $_POST["usedIngredients"]);
$ingredients = array();
for ($i = 0; $i < count($lines) - 1; $i++) {
    $ingr = explode(",",$lines[$i]);
    $ingr[1] = trim($ingr[1]);
    array_push($ingredients, $ingr);
}

if (isset($_POST["add"])) {
    try {
        $dbh->addRecipe(
            $_SESSION["nickname"],
            $_POST["title"],
            isset($_POST["public"]) ? 1 : 0,
            $_POST["preparation"],
            $_POST["preparationTime"],
            $_POST["portions"],
            $ingredients
        );
        header("Location: " . ROOT . "template/my-recipes.php");
    } catch (Exception $e) {
        $_SESSION["recipeError"] = $e->getMessage();
        header("Location: " . ROOT . "template/recipe-crud.php");
    }
}

/*
try {
    $dbh->login($_POST["nickname"], $_POST["password"]);
    // login riuscito, reindirizzamento alla home page
    header("Location: " . ROOT . "template/home.php");
} catch (Exception $e) {
    $_SESSION["loginError"] = $e->getMessage();
    // login fallito, ricaricamento del login form con l'errore in evidenza
    header("Location: " . ROOT . "template/user-login.php");
}
*/



?>