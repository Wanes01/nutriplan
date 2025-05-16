<?php
require_once "../bootstrap.php";
session_start();
$_SESSION = array();
session_destroy();
header("Location: " . ROOT . "index.php");
?>