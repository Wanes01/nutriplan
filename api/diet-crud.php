<?php
require_once "../bootstrap.php";

// utente NON loggato, redirect alla pagina di benvenuto
if (!isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "index.php");
}

if (isset($_POST['add'])) {
    try {
        $dbh->addDiet($_SESSION['nickname'], $_POST['name']);
    } catch (Exception $e) {
        $_SESSION['dietError'] = $e->getMessage();
    }
    header("Location: " . ROOT . "template/my-diets.php");
} else if (isset($_POST['update'])) {
    $dbh->addToDiet($_POST['title'], $_POST['editor'], $_SESSION['nickname'], $_POST['toInclude']);
} else if (isset($_GET['del'])) {
    $dietName = rawurldecode($_GET['dName']);
    $title = rawurldecode($_GET['title']);
    $editor = rawurldecode($_GET['editor']);
    $dbh->removeRecipeFromDiet($dietName, $_SESSION['nickname'], $title, $editor);
} else if (isset($_GET['delete'])) {
    $dbh->deleteDiet(rawurldecode($_GET['name']), $_SESSION['nickname']);
}

header("Location: " . $_SERVER['HTTP_REFERER']);

?>