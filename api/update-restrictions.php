<?php
require_once "../bootstrap.php";

if (!isset($_SESSION["nickname"]) || $_SESSION["role"] != "amministratori") {
    header("Location: " . ROOT . "index.php");
    return;
}

$dbh->updateRestrictions();

header("Location: " . ROOT . "template/home.php");
?>