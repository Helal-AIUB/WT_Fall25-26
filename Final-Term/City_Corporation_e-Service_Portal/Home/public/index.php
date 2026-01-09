<?php
require_once '../config/db.php'; 
require_once '../controllers/AuthController.php';

// Corrected initialization
$controller = new AuthController($pdo); 
$controller->handleRequest();

// The only place the view is included to prevent duplication
include '../views/auth_view.php'; 
?>