<?php
require_once '../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        // Renamed to 'Users' to match your model
        $this->userModel = new Users($pdo);
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['signup'])) {
                $this->processSignup();
            }
            if (isset($_POST['signin'])) {
                $this->processSignin();
            }
        }
    }

    private function processSignup() {
        $name = $this->test_input($_POST["name"]);
        $email = $_POST["email"];
        $role = $_POST["role"] ?? ""; 
        $password = $_POST["password"];
        $confirm = $_POST["confirm_password"];

        // 1. Basic validation
        if (empty($name) || empty($email) || empty($role) || empty($password)) {
            echo "<script>alert('Please fill in all fields.');</script>";
            return;
        }

        // 2. Name validation (Your pattern)
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            echo "<script>alert('Only letters and white space allowed in Name');</script>";
            return;
        }

        // 3. Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            echo "<script>alert('This email is already registered. Please use a different one or Sign In.');</script>";
            return;
        }

        // 4. Password match check
        if ($password !== $confirm) {
            echo "<script>alert('Passwords do not match!');</script>";
            return;
        }

        // 5. Register via Model
        if ($this->userModel->register($name, $email, $role, $password)) {
            echo "<script>alert('Registration Complete!');</script>";
        }
    }

//     private function processSignin() {
//         $email = trim($_POST['signin_email']);
//        $password = $_POST['signin_password'];

//     if (empty($email) || empty($password)) {
//         echo "<script>alert('Please fill in all login fields.');</script>";
//         return;
//     }

//     $user = $this->userModel->findByEmail($email);

//     if ($user && password_verify($password, $user['password'])) {
//         if (session_status() === PHP_SESSION_NONE) {
//             session_start();
//         }
        
//         // Store user data in session
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['user_name'] = $user['name'];
//         $_SESSION['user_role'] = $user['role'];

//         // Redirect based on role
//         if ($user['role'] === 'citizen') {
//             header("Location: ../Citizen/public/index.php");
//             exit(); // Always use exit() after a header redirect
//         } else if ($user['role'] === 'official') {
//             header("Location: ../Official/public/index.php");
//             exit();
//         } else {
//             header("Location: ../Counselor/public/index.php");
//             exit();
//         }
//     } else {
//         echo "<script>alert('Invalid Email or Password');</script>";
//     }
// }

private function processSignin() {
        $email = trim($_POST['signin_email']);
        $password = $_POST['signin_password'];

        if (empty($email) || empty($password)) {
            echo "<script>alert('Please fill in all login fields.');</script>";
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // JavaScript Redirect logic to avoid "Header already sent" errors
            $redirectPath = "";
            if ($user['role'] === 'citizen') {
                //$redirectPath = "../Citizen/public/index.php";
                $redirectPath = "../../Citizen/public/index.php";
            } else if ($user['role'] === 'official') {
                $redirectPath = "../../Official/public/index.php";
            } else {
                $redirectPath = "../../Counselor/public/index.php";
            }

            echo "<script>
                alert('Welcome back, " . $user['name'] . "!');
                window.location.href = '$redirectPath';
            </script>";
            exit(); 
        } else {
            echo "<script>alert('Invalid Email or Password');</script>";
        }
    }

    private function test_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
?>