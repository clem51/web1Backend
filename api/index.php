<?php
header("Content-Type: application/json");

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dashboard_db;port=3306;',
        'root',
        'root',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ]
    );
}catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();
};
$query = $pdo->prepare("SELECT * FROM `songs`");
$query->execute();
$results= $query->fetch(PDO::FETCH_ASSOC);

echo json_encode($results);
