<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

if (isset($_POST["usedIngredients"])) {
    $lines = explode("\n", $_POST["usedIngredients"]);
    $ingredients = array();
    for ($i = 0; $i < count($lines) - 1; $i++) {
        $ingr = explode(",",$lines[$i]);
        $ingr[1] = trim($ingr[1]);
        array_push($ingredients, $ingr);
    }
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
} else if (isset($_POST["update"])) {
    try {
        $dbh->updateRecipe(
            $_SESSION["nickname"],
            $_POST["oldTitle"],
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
} else if (isset($_GET["del"])) {
    try {
        $dbh->deleteRecipe($_SESSION["nickname"], rawurldecode($_GET["title"]));
    } catch (Exception $e) {
        $_SESSION["recipeError"] = $e->getMessage();
        error_log($e->getMessage());
    }
}

header("Location: " . ROOT . "template/my-recipes.php");

?>