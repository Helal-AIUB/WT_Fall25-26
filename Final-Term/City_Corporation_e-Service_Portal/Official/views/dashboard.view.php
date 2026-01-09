<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Dashboard | City Corp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        /* Extra styles for the Official Table */
        .status-form { display: inline-block; }
        .btn-action { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 12px; margin-right: 5px;}
        .btn-approve { background-color: #27ae60; }
        .btn-reject { background-color: #e74c3c; }
        .btn-approve:hover { background-color: #219150; }
        .btn-reject:hover { background-color: #c0392b; }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <nav class="sidebar" style="background: linear-gradient(180deg, #2c3e50, #4ca1af);">
            <div class="brand"><h2><i class="fa fa-university"></i> City Admin</h2></div>
            <div class="user-profile-preview">
                <div><h4><?php echo $_SESSION['user_name']; ?></h4><small>Official Authority</small></div>
            </div>
            <ul class="menu">
                <li class="active"><a href="#"><i class="fa fa-th-large"></i> Overview</a></li>
                <li><a href="#"><i class="fa fa-users"></i> Citizens</a></li>
                <li class="logout"><a href="../../Home/public/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <h1>Official Dashboard</h1>
            </header>

            <section class="services-grid" style="margin-bottom: 30px;">
                <div class="service-card" style="border-left: 5px solid #2da0a8;">
                    <h3>Total Applications</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?php echo $stats['total']; ?></p>
                </div>
                <div class="service-card" style="border-left: 5px solid #f1c40f;">
                    <h3>Pending Requests</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?php echo $stats['pending']; ?></p>
                </div>
                <div class="service-card" style="border-left: 5px solid #27ae60;">
                    <h3>Approved Licenses</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?php echo $stats['approved']; ?></p>
                </div>
            </section>

            <div class="table-container" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px; color: #2c3e50;">Manage Trade Licenses</h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background:#f8f9fa; text-align:left;">
                            <th style="padding:12px;">Applicant</th>
                            <th style="padding:12px;">Business Name</th>
                            <th style="padding:12px;">Payment Info</th>
                            <th style="padding:12px;">Status</th>
                            <th style="padding:12px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding:12px;">
                                <strong><?php echo htmlspecialchars($app['applicant_name']); ?></strong><br>
                                <small>NID: <?php echo htmlspecialchars($app['nid']); ?></small>
                            </td>
                            <td style="padding:12px;"><?php echo htmlspecialchars($app['business_name']); ?></td>
                            <td style="padding:12px;">
                                <?php echo $app['payment_method']; ?><br>
                                <small>Trx: <?php echo $app['trx_id']; ?></small>
                            </td>
                            <td style="padding:12px;">
                                <?php 
                                    $color = 'orange';
                                    if($app['status'] == 'approved') $color = 'green';
                                    if($app['status'] == 'rejected') $color = 'red';
                                ?>
                                <span style="color:<?php echo $color; ?>; font-weight:bold;">
                                    <?php echo ucfirst($app['status']); ?>
                                </span>
                            </td>
                            <td style="padding:12px;">
                                <?php if ($app['status'] === 'pending'): ?>
                                    <form action="index.php" method="POST" class="status-form">
                                        <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this license?')"><i class="fa fa-check"></i></button>
                                    </form>
                                    
                                    <form action="index.php" method="POST" class="status-form">
                                        <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn-action btn-reject" onclick="return confirm('Reject this application?')"><i class="fa fa-times"></i></button>
                                    </form>
                                <?php else: ?>
                                    <small style="color:#aaa;">No actions</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>