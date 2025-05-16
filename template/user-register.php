<?php
require_once "../bootstrap.php";

// utente giá loggato, redirect alla home
if (isset($_SESSION['nickname'])) {
    header("Location: " . ROOT . "template/home.php");
}

$params = [
    "title" => "Registrazione | NutriPlan",
    "main" => "./main/register-main.php"
];

require_once "./base.php";
?>