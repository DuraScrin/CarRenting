<?php
$host = 'lqut.your-database.de'; // Remote database host
$dbname = 'car_rental'; // Database name
$username = 'randymarsh'; // Remote database username
$password = 'y2)X;7{(c}E$'; // Remote database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>