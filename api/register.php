<?php
require_once "../bootstrap.php";

try {
    $dbh->register($_POST["name"], $_POST["surname"], $_POST["nickname"], $_POST["password"]);
    // login riuscito, reindirizzamento al login
    header("Location: " . ROOT . "template/user-login.php");
} catch (Exception $e) {
    $_SESSION["registerError"] = $e->getMessage();
    header("Location: " . ROOT . "template/user-register.php");
}

?>