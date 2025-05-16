<?php
require_once "../bootstrap.php";

/* aggiunta, modifica ed eliminazione di ingredienti sono azioni
permesse solo agli amministratori */
if (!isset($_SESSION["nickname"]) || $_SESSION["role"] != "amministratori") {
    header("Location: " . ROOT . "template/welcome.php");
    return;
}

// aggiunta di un ingrediente
if (isset($_POST["add"])) {
    try {
        $dbh->addIngredient(
            $_POST['name'],
            $_POST['kcal'],
            $_POST['price'],
            $_POST['carbs'],
            $_POST['proteins'],
            $_POST['unsFat'],
            $_POST['satFat'],
            $_POST['unit']
        );
        $_SESSION['success'] = "Ingrediente aggiunto!";
    } catch (Exception $e) {
        $_SESSION['ingredientError'] = "Esiste giá un ingrediente con questo nome";
    }
// modifica di un ingrediente
} else if (isset($_POST["update"])) {
    var_dump($_POST);
    try {
        $dbh->updateIngredient(
            $_POST['oldName'],
            $_POST['name'],
            $_POST['kcal'],
            $_POST['price'],
            $_POST['carbs'],
            $_POST['proteins'],
            $_POST['unsFat'],
            $_POST['satFat'],
            $_POST['unit']
        );
        $_SESSION['success'] = "Ingrediente modificato!";
    } catch (Exception $e) {
        $_SESSION['ingredientError'] = $e->getMessage();
    }
// eliminazione dell'ingrediente
} else if (isset($_POST["delete"])) {
    try {
        $dbh->deleteIngredient($_POST["oldName"]);
        $_SESSION['success'] = "Ingrediente eliminato!";
    } catch (Exception $e) {
        $_SESSION['ingredientError'] = $e->getMessage();
    }
}

header("Location: " . ROOT . "template/home.php");

?>