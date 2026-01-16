<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NID Correction | City Corp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .form-section { background: white; padding: 30px; border-radius: 12px; max-width: 800px; margin: 20px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #555; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        .btn-submit { background: #3498db; color: white; width: 100%; padding: 15px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        
        <nav class="sidebar">
            <div class="brand">
                <h2><i class="fa fa-building"></i> CityCorp</h2>
            </div>
            <div class="user-profile-preview">
                <img src="uploads/<?php echo $user['profile_pic'] ?? 'default.png'; ?>" alt="Profile">
                <div>
                    <h4><?php echo $_SESSION['user_name']; ?></h4>
                    <small>Citizen</small>
                </div>
            </div>
            <ul class="menu">
                <li><a href="index.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
                <li><a href="profile.php"><i class="fa fa-user-cog"></i> My Profile</a></li>
                <li><a href="applications.php"><i class="fa fa-file-alt"></i> My Applications</a></li>
                <li><a href="billing.php"><i class="fa fa-credit-card"></i> Pay Bills</a></li>
                <li class="logout"><a href="../../Home/public/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="form-section">
                <h2 style="color:#2c3e50; border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:25px;">
                    <i class="fa fa-id-card"></i> NID Correction Application
                </h2>

                <form action="nid_correction.php" method="POST">
                    
                    <div class="form-group">
                        <label>Your Current NID Number</label>
                        <input type="text" name="current_nid" value="<?php echo $user['nid']; ?>" readonly style="background:#f9f9f9;">
                    </div>

                    <div class="form-group">
                        <label>What do you want to correct?</label>
                        <select name="correction_type" required>
                            <option value="" disabled selected>Select Correction Type</option>
                            <option value="Name Correction">Name Correction</option>
                            <option value="Date of Birth">Date of Birth Correction</option>
                            <option value="Address Update">Address Update</option>
                            <option value="Photo Change">Photo Change</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Correction Details (What is the correct info?)</label>
                        <textarea name="details" rows="5" placeholder="Example: My name is spelled 'Kamrul', but on NID it is 'Kamrul'. Correct spelling should be..." required></textarea>
                    </div>

                    <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p style="color: #0d47a1; font-weight: bold;">
                            <i class="fa fa-info-circle"></i> Service Fee: 200 BDT
                        </p>
                        <small>You can pay this fee from the "Pay Bills" section after submitting.</small>
                    </div>

                    <button type="submit" name="apply_nid" class="btn-submit">Submit Application</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>