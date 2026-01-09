<?php
session_start();

// 1. Security: Only 'official' role allowed
// (Make sure your user in database has role='official')
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'official') {
    header("Location: ../../Home/public/index.php");
    exit();
}

require_once '../config/db.php';
require_once '../controllers/OfficialController.php';

$controller = new OfficialController($pdo);

// 2. Route Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller->handleStatusUpdate();
} else {
    $controller->showDashboard();
}
?>