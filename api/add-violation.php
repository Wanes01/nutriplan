<?php
require_once "../bootstrap.php";

if (!isset($_SESSION["nickname"]) || $_SESSION["role"] != "amministratori") {
    header("Location: " . ROOT . "index.php");
    return;
}

$dbh->registerViolation(
    $_SESSION['nickname'],
    $_POST['reason'],
    $_POST['recEvaluator'],
    $_POST['recTitle'],
    $_POST['recEditor']
);

header("Location: " . ROOT . "template/recipe.php?nickname=" . rawurlencode($_POST['recEditor']) . "&title=" . rawurlencode($_POST['recTitle']));
?>