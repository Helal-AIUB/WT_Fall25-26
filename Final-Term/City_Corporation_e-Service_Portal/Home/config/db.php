<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "City_Corporation_e-Service_Portal"; // Double-check this name in phpMyAdmin!

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If this fails, it will tell you why instead of showing a blank screen
    die("Database Connection Error: " . $e->getMessage());
}
?>