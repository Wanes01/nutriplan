<?php
require_once "bootstrap.php";

// utente giá loggato, redirect alla home
if (isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "template/home.php");
}

$params = [
    "title" => "NutriPlan | Benvenuto!",
    "main" => "./template/main/welcome.php"
];

require_once "./template/base.php";
?>