<?php
require_once "../bootstrap.php";

try {
    $dbh->addEvaluation($_POST['title'], $_POST['editor'], $_SESSION['nickname'], $_POST['vote'], $_POST['comment']);
} catch (Exception $e) {
    $_SESSION['commentError'] = $e->getMessage();
}

header("Location: " . ROOT . "template/recipe.php?nickname=" . rawurlencode($_POST['editor']) . "&title=" . rawurlencode($_POST['title']));

?>