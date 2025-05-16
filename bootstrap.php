<?php

session_start();

require_once("utility.php");
require_once("db/DatabaseHelper.php");

// Stabilisce una connessione riutilizzabile al database
$dbh = new DatabaseHelper(
    "localhost",
    "root",
    "",
    "nutriplan",
    3306
);

define("ROOT", "/nutriplan/");
define("TEMPL", "./template/");

?>