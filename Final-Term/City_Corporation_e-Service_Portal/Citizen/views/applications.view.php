<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications | City Corp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .table-container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .app-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .app-table th { text-align: left; padding: 12px; background: #f8f9fa; color: #555; border-bottom: 2px solid #eee; }
        .app-table td { padding: 12px; border-bottom: 1px solid #eee; color: #333; vertical-align: middle; }
        .app-table tr:hover { background-color: #f9f9f9; }
        
        /* Status Badges */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
        .badge.pending { background: #fff3cd; color: #856404; }
        .badge.approved { background: #d4edda; color: #155724; }
        .badge.rejected { background: #f8d7da; color: #721c24; }

        /* Button Styles */
        .btn-cert {
            background: #2c3e50; 
            color: white; 
            padding: 6px 12px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 12px; 
            transition: 0.3s;
        }
        .btn-cert:hover { background: #1a252f; }
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
                <li class="active"><a href="applications.php"><i class="fa fa-file-alt"></i> My Applications</a></li>
                <li><a href="billing.php"><i class="fa fa-credit-card"></i> Pay Bills</a></li>
                <li class="logout"><a href="../../Home/public/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <div class="welcome-text">
                    <h1>My Applications</h1>
                    <p>Track the status of your service requests.</p>
                </div>
            </header>

            <div class="table-container">
                <h3 style="color:#2c3e50; margin-bottom:10px;">Trade License History</h3>
                
                <?php if (empty($tradeLicenses)): ?>
                    <div style="text-align:center; padding:40px; color:#777;">
                        <i class="fa fa-folder-open" style="font-size:40px; margin-bottom:10px; opacity:0.5;"></i>
                        <p>You have not applied for any trade licenses yet.</p>
                    </div>
                <?php else: ?>
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Business Name</th>
                                <th>Applied Date</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th style="text-align: center;">Certificate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tradeLicenses as $license): ?>
                            <tr>
                                <td>
                                    <i class="fa fa-file-contract" style="color:#ff9800; margin-right:5px;"></i> 
                                    Trade License
                                </td>

                                <td>
                                    <strong><?php echo htmlspecialchars($license['business_name']); ?></strong>
                                </td>

                                <td><?php echo date("d M Y", strtotime($license['applied_at'])); ?></td>

                                <td>
                                    <?php if($license['payment_status'] == 'Paid'): ?>
                                        <span style="color:#27ae60; font-weight:bold; font-size:13px;">
                                            <i class="fa fa-check-circle"></i> Paid
                                        </span><br>
                                        <small style="color:#666;">Via <?php echo $license['payment_method']; ?></small>
                                    <?php else: ?>
                                        <span style="color:#e74c3c; font-weight:bold; font-size:13px;">
                                            <i class="fa fa-times-circle"></i> Unpaid
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="badge <?php echo strtolower($license['status']); ?>">
                                        <?php echo ucfirst($license['status']); ?>
                                    </span>
                                </td>

                                <td style="text-align: center;">
                                    <?php if (strtolower($license['status']) === 'approved' && $license['payment_status'] === 'Paid'): ?>
                                        <a href="download_certificate.php?id=<?php echo $license['id']; ?>" target="_blank" style="text-decoration:none;">
                                            <button class="btn-cert">
                                                <i class="fa fa-download"></i> Download
                                            </button>
                                        </a>
                                    <?php elseif (strtolower($license['status']) === 'rejected'): ?>
                                        <span style="color:#e74c3c; font-size: 12px;">Contact Admin</span>
                                    <?php else: ?>
                                        <span style="color:#ccc; font-size: 12px; font-style:italic;">Processing...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>