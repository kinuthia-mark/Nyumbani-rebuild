<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Fetch Quick Stats
$count_news = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM newsletters"))['total'];
$count_annual = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM annual_reports"))['total'];
$count_audit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM audit_reports"))['total'];
$count_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM job_openings"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nyumbani Admin | Dashboard</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex !important; background: #f4f7f6 !important; margin: 0; min-height: 100vh; font-family: 'Poppins', sans-serif; }
        .admin-main { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card i { font-size: 30px; margin-bottom: 10px; color: #4175FC; }
        .stat-card h3 { margin: 10px 0 5px; font-size: 24px; color: #062269; }
        .stat-card p { color: #666; margin: 0; font-size: 14px; }

        /* Welcome Section */
        .welcome-banner { margin-bottom: 30px; }
        .welcome-banner h1 { color: #062269; margin: 0; }
        .welcome-banner p { color: #666; }

        .quick-actions { display: flex; gap: 15px; margin-top: 20px; }
        .action-btn { background: #4175FC; color: white; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.3s; }
        .action-btn:hover { background: #062269; }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>
    
    <main class="admin-main">
        <section class="welcome-banner">
            <h1>Hello, Admin</h1>
            <p>Welcome back! Here is a summary of Nyumbani's digital presence.</p>
            
            <div class="quick-actions">
                <a href="manage_newsletters.php" class="action-btn"><i class="fas fa-plus"></i> New Newsletter</a>
                <a href="manage_jobs.php" class="action-btn"><i class="fas fa-plus"></i> New Job Opening</a>
                <a href="manage_audit_reports.php" class="action-btn"><i class="fas fa-plus"></i> New Audit Report</a>
                <a href="manage_annual_reports.php" class="action-btn"><i class="fas fa-plus"></i> New Annual Report</a>
                <a href="manage_blog.php" class="action-btn"><i class="fas fa-edit"></i> Write Blog Post</a>
            </div>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-newspaper"></i>
                <h3><?php echo $count_news; ?></h3>
                <p>Newsletters</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3><?php echo $count_annual; ?></h3>
                <p>Annual Reports</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-file-invoice-dollar"></i>
                <h3><?php echo $count_audit; ?></h3>
                <p>Audits</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-briefcase"></i>
                <h3><?php echo $count_jobs; ?></h3>
                <p>Open Careers</p>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h2 style="margin-top:0; color: #062269;">System Status</h2>
            <p><i class="fas fa-check-circle" style="color: #27ae60;"></i> Database Connected</p>
            <p><i class="fas fa-check-circle" style="color: #27ae60;"></i> File Upload System Active</p>
            <p><i class="fas fa-info-circle" style="color: #4175FC;"></i> All website documents are currently up to date.</p>
        </div>
    </main>

</body>
</html>