<?php
/* Attempt to connect to MySQL database */
try{
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dashboard_db;port=3306;',
        'root',
        'root',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ]
    );
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>