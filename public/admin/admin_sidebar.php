<?php
// Get the current file name to highlight the active link
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    /* 1. The Sidebar Container */
    .admin-sidebar {
        width: 260px;
        background: #062269;
        color: white;
        padding: 25px 15px;
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        box-sizing: border-box;
        overflow-y: auto;
        z-index: 1000;
    }

    /* 2. The Button/Link Styles */
    .admin-nav-link {
        color: #bdc3c7 !important;
        text-decoration: none !important;
        padding: 12px 15px; 
        border-radius: 8px;
        margin-bottom: 5px; /* Slightly reduced margin to fit more links */
        display: flex; 
        align-items: center;
        transition: 0.3s;
        font-family: 'Poppins', sans-serif;
        font-size: 14px; /* Slightly smaller to accommodate the new links */
    }

    .admin-nav-link i {
        margin-right: 15px;
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .admin-nav-link:hover, 
    .admin-nav-link.active {
        background: #4175FC !important;
        color: white !important;
    }

    /* Sub-section Heading Style */
    .nav-label {
        font-size: 11px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.4);
        margin: 20px 0 10px 15px;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .admin-nav-container { flex: 1; }

    .admin-sidebar-footer {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .admin-sidebar h2 {
        font-size: 20px;
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 10px;
        color: white !important;
    }
</style>

<aside class="admin-sidebar">
    <div class="admin-nav-container">
        <h2>Nyumbani Admin</h2>
        
        <a href="manage_gallery.php" class="admin-nav-link <?php echo ($current_page == 'manage_gallery.php') ? 'active' : ''; ?>">
            <i class="fas fa-images"></i> Manage Gallery
        </a>
        
        <a href="manage_blog.php" class="admin-nav-link <?php echo ($current_page == 'manage_blog.php') ? 'active' : ''; ?>">
            <i class="fas fa-blog"></i> Blog Posts
        </a>

        <a href="manage_jobs.php" class="admin-nav-link <?php echo ($current_page == 'manage_jobs.php') ? 'active' : ''; ?>">
            <i class="fas fa-briefcase"></i> Manage Careers
        </a>

        <div class="nav-label">Resource Archive</div>

        <a href="manage_newsletters.php" class="admin-nav-link <?php echo ($current_page == 'manage_newsletters.php') ? 'active' : ''; ?>">
            <i class="fas fa-newspaper"></i> Newsletters
        </a>

        <a href="manage_annual_reports.php" class="admin-nav-link <?php echo ($current_page == 'manage_annual_reports.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> Annual Reports
        </a>

        <a href="manage_audit_reports.php" class="admin-nav-link <?php echo ($current_page == 'manage_audit_reports.php') ? 'active' : ''; ?>">
            <i class="fas fa-file-invoice-dollar"></i> Audit Reports
        </a>

        <a href="manage_messages.php" class="admin-nav-link <?php echo ($current_page == 'manage_messages.php') ? 'active' : ''; ?>">
    <i class="fas fa-envelope"></i> Messages
</a>
    </div>

    <div class="admin-sidebar-footer">
        <a href="../index.php" class="admin-nav-link" target="_blank">
            <i class="fas fa-globe"></i> Visit Website
        </a>
        <a href="logout.php" class="admin-nav-link" style="color: #ff7675 !important;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>