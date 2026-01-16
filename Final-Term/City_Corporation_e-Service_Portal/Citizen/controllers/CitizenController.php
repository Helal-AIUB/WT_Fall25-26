<?php
require_once '../models/CitizenModel.php';

class CitizenController {
    private $model;

    public function __construct($pdo) {
        $this->model = new CitizenModel($pdo);
    }

    // 1. Load the Dashboard View with User Data
    public function showDashboard() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $userId = $_SESSION['user_id'];
        $user = $this->model->getProfile($userId); // Fetch current data
        
        // Pass data to the view
        include '../views/dashboard.view.php';
    }

    public function showProfilePage() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $userId = $_SESSION['user_id'];
        $user = $this->model->getProfile($userId); // Reusing your existing model method
        
        include '../views/profile.view.php';
    }

    // 2. Handle Profile Updates (POST request)
    public function updateProfile() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $nid = $_POST['nid'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            
            // Handle File Upload (Profile Picture)
            $profilePic = null;
            if (!empty($_FILES['profile_pic']['name'])) {
                $targetDir = "../public/uploads/";
                
                // Create folder if it doesn't exist
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
                    $profilePic = $fileName;
                }
            }

            // Save to Database
            if ($this->model->updateInfo($userId, $nid, $phone, $address, $profilePic)) {
                echo "<script>
                        alert('Profile Updated Successfully!');
                        window.location.href = 'index.php';
                      </script>";
            } else {
                echo "<script>alert('Update Failed');</script>";
            }
        }
    }


    // --- TRADE LICENSE FUNCTIONS ---

   public function showTradeLicenseForm() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'];
        
        // 1. Fetch current user data
        $user = $this->model->getProfile($userId);
        
        // 2. CHECK: Is the profile complete?
        // We check if NID, Phone, or Address are empty or null
        if (empty($user['nid']) || empty($user['phone']) || empty($user['address'])) {
            
            // 3. If incomplete: Show Alert & Redirect to Profile Page
            echo "<script>
                    alert('⚠️ Action Required! \\n\\nYou must complete your Profile Information (NID, Phone Number, and Address) before applying for a Trade License.');
                    window.location.href = 'profile.php';
                  </script>";
            exit(); // Stop the script here
        }

        // 4. If complete: Show the Application Form
        include '../views/trade_license.view.php';
    }

    // [Step 1] SUBMIT APPLICATION (Apply First, Pay Later)
    public function processTradeLicense() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'];
        
        // 1. Get Form Data
        $bName = $_POST['business_name'];
        $bType = $_POST['business_type'];
        $bAddress = $_POST['business_address'];
        $capital = $_POST['trade_capital'];

        // 2. Logic: Calculate Fee based on Capital
        // Example: If capital > 5 Lakh, fee is 5000, else 2000
        $fee = ($capital > 500000) ? 5000 : 2000; 

        // 3. Set Defaults for "Apply First"
        $paymentStatus = 'Unpaid';
        $payMethod = NULL; // No payment yet
        $trxId = NULL;     // No Trx ID yet

        // 4. Save to DB 
        // Note: Make sure your Model's createTradeLicense function accepts these 9 variables!
        if ($this->model->createTradeLicense($userId, $bName, $bType, $bAddress, $capital, $payMethod, $trxId, $fee, $paymentStatus)) {
             echo "<script>
                    alert('Application Submitted! A bill for $fee BDT has been generated. Please go to Pay Bills.');
                    window.location.href = 'billing.php'; // Redirect to Pay Bills
                  </script>";
        } else {
            echo "<script>alert('Application Failed. Please try again.');</script>";
        }
    }
    // --- MY APPLICATIONS PAGE ---
    public function showMyApplications() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'];
        
        $user = $this->model->getProfile($userId);
        $tradeLicenses = $this->model->getMyTradeLicenses($userId);
        
        // --- ADD THIS LINE ---
        $nidApplications = $this->model->getNIDApplications($userId); 
        // --------------------
        
        include '../views/applications.view.php';
    }

    // --- BILLING CONTROLLER LOGIC ---

    public function showBillingPage() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'];
        $user = $this->model->getProfile($userId);
        
        // 1. Get Trade License Bills
        $tradeBills = $this->model->getUnpaidBills($userId);
        
        // 2. Get NID Bills (We need to filter for 'Unpaid' manually or add a model function)
        // Let's add a quick helper in the model or just filter here.
        // Better: Add getUnpaidNIDBills to Model.
        $nidBills = $this->model->getUnpaidNIDBills($userId); 

        // 3. Merge them for the View
        // We will pass them separately to keep the view clean
        
        include '../views/billing.view.php';
    }
/*
    public function processBillPayment() {
        $billId = $_POST['bill_id'];
        $method = $_POST['payment_method'];
        $trxId  = $_POST['trx_id'];

        if ($this->model->payBill($billId, $method, $trxId)) {
            echo "<script>
                    alert('Payment Successful! The official will review it soon.');
                    window.location.href = 'billing.php';
                  </script>";
        } else {
            echo "<script>alert('Payment Failed. Try again.');</script>";
        }
    }
        */
    public function processBillPayment() {
        // 1. Get the Hidden Type from the Form
        $billType = $_POST['bill_type']; // Must be 'trade' or 'nid'
        $billId = $_POST['bill_id'];
        $method = $_POST['payment_method'];
        $trxId  = $_POST['trx_id'];

        $success = false;

        // 2. Switch Logic
        if ($billType === 'trade') {
            $success = $this->model->payBill($billId, $method, $trxId);
        } elseif ($billType === 'nid') {
            // This function MUST exist in your Model
            $success = $this->model->payNIDBill($billId, $method, $trxId);
        }

        // 3. Result
        if ($success) {
            echo "<script>
                    alert('Payment Successful!');
                    window.location.href = 'billing.php';
                  </script>";
        } else {
            echo "<script>alert('Payment Failed. Status did not update.');</script>";
        }
    }

    // --- NID CORRECTION FUNCTIONS ---

    public function showNIDForm() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'];
        $user = $this->model->getProfile($userId);
        
        // Basic Profile Check
        if (empty($user['nid'])) {
            echo "<script>alert('Please update your Profile with your NID number first!'); window.location.href='profile.php';</script>";
            exit();
        }

        include '../views/nid_correction.view.php';
    }

    public function processNIDCorrection() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'];
        
        $correctionType = $_POST['correction_type'];
        $details = $_POST['details'];
        $currentNid = $_POST['current_nid']; // From hidden field or user input

        if ($this->model->createNIDApplication($userId, $currentNid, $correctionType, $details)) {
             echo "<script>
                    alert('Application Submitted! Please go to Pay Bills to complete the process.');
                    window.location.href = 'billing.php'; 
                  </script>";
        } else {
            echo "<script>alert('Application Failed. Please try again.');</script>";
        }
    }
    
}
?>