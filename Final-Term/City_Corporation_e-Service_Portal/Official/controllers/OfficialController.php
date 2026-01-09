<?php
require_once '../models/OfficialModel.php';

class OfficialController {
    private $model;

    public function __construct($pdo) {
        $this->model = new OfficialModel($pdo);
    }

    public function showDashboard() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Fetch data for the view
        $stats = $this->model->getStats();
        $applications = $this->model->getAllApplications();
        
        include '../views/dashboard.view.php';
    }

    public function handleStatusUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appId = $_POST['application_id'];
            $action = $_POST['action']; // 'approve' or 'reject'

            $status = ($action === 'approve') ? 'approved' : 'rejected';

            if ($this->model->updateApplicationStatus($appId, $status)) {
                echo "<script>
                        alert('Application " . ucfirst($status) . " Successfully!');
                        window.location.href = 'index.php';
                      </script>";
            } else {
                echo "<script>alert('Error updating status'); window.location.href = 'index.php';</script>";
            }
        }
    }
}
?>