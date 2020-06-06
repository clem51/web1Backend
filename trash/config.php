<?php
require_once("./database.php");
ini_set('display_errors', 1);

/* Attempt to connect to MySQL database */
$db = new Database('mysql:host=localhost;dbname=dashboard_db;port=3306;', 'root', 'root');
?>