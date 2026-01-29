<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
include 'db.php';

// Handle Deletion
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM job_openings WHERE id = $id");
    header("Location: manage_jobs.php");
    exit;
}

// Handle Addition
if(isset($_POST['add_job'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $loc = mysqli_real_escape_string($conn, $_POST['location']);
    $type = mysqli_real_escape_string($conn, $_POST['job_type']);
    $cat = $_POST['category'];
    
    // 1. Capture the admin's name from the session
    $uploader = $_SESSION['admin_name']; 
    
    // 2. Updated SQL: Now includes the 'uploaded_by' column
    mysqli_query($conn, "INSERT INTO job_openings (title, location, job_type, category, uploaded_by) 
                         VALUES ('$title', '$loc', '$type', '$cat', '$uploader')");
    
    header("Location: manage_jobs.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Careers | Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex !important; background: #f4f7f6 !important; margin: 0; text-align: left !important; font-family: 'Poppins', sans-serif; }
        .admin-main { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        .admin-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-save { background: #4175FC; color: white; padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .job-row { background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #eee; transition: 0.3s; }
        .job-row:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        
        .posted-by { font-size: 11px; color: #4175FC; background: #eef2ff; padding: 2px 8px; border-radius: 4px; font-weight: 600; text-transform: capitalize; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    
    <main class="admin-main">
        <div class="admin-card">
            <h2><i class="fas fa-plus-circle"></i> Post a New Job</h2>
            <form method="POST">
                <input type="text" name="title" placeholder="Job Title (e.g. Registered Nurse)" required>
                <input type="text" name="location" placeholder="Location (e.g. Karen, Nairobi)" required>
                <select name="job_type">
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Contract">Contract</option>
                </select>
                <select name="category">
                    <option value="medical">Medical</option>
                    <option value="social">Social Work</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="add_job" class="btn-save">Post Job Opening</button>
            </form>
        </div>

        <h3>Active Openings</h3>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM job_openings ORDER BY created_at DESC");
        while($row = mysqli_fetch_assoc($res)) {
            // Check if uploaded_by exists, otherwise default to Admin
            $admin_name = !empty($row['uploaded_by']) ? $row['uploaded_by'] : 'Admin';

            echo "<div class='job-row'>
                    <div>
                        <span class='posted-by'><i class='fas fa-user-edit'></i> Posted by: ".$admin_name."</span><br>
                        <strong style='font-size: 16px; color: #062269;'>".$row['title']."</strong><br>
                        <small style='color: #666;'>".$row['location']." | ".$row['job_type']." (Category: ".$row['category'].")</small>
                    </div>
                    <a href='?delete=".$row['id']."' style='color:#e74c3c; font-size: 18px;' onclick='return confirm(\"Delete this job?\")'>
                        <i class='fas fa-trash'></i>
                    </a>
                  </div>";
        }
        ?>
    </main>
</body>
</html>