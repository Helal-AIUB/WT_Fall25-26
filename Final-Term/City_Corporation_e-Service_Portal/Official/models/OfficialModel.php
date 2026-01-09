<?php
class OfficialModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // 1. Fetch ALL applications with User Details
    public function getAllApplications() {
        // We join 'trade_licenses' with 'users' to get the applicant's name
        $sql = "SELECT t.*, u.name as applicant_name, u.nid 
                FROM trade_licenses t 
                JOIN users u ON t.user_id = u.id 
                ORDER BY t.applied_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Approve or Reject an Application
    public function updateApplicationStatus($applicationId, $status) {
        $sql = "UPDATE trade_licenses SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $applicationId]);
    }

    // 3. Get Stats for Dashboard Cards
    public function getStats() {
        // Count Total, Pending, and Approved
        $total = $this->db->query("SELECT COUNT(*) FROM trade_licenses")->fetchColumn();
        $pending = $this->db->query("SELECT COUNT(*) FROM trade_licenses WHERE status='pending'")->fetchColumn();
        $approved = $this->db->query("SELECT COUNT(*) FROM trade_licenses WHERE status='approved'")->fetchColumn();
        
        return ['total' => $total, 'pending' => $pending, 'approved' => $approved];
    }
}
?>