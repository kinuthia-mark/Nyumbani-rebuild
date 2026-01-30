<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// --- HANDLE DELETION ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // First, get the file path to delete it from the server
    $res = mysqli_query($conn, "SELECT pdf_path FROM annual_reports WHERE id = $id");
    $file = mysqli_fetch_assoc($res);
    if ($file) unlink("../" . $file['pdf_path']); // Remove file

    mysqli_query($conn, "DELETE FROM annual_reports WHERE id = $id");
    header("Location: manage_annual_reports.php?deleted=1");
    exit;
}

// --- HANDLE UPLOAD ---
if (isset($_POST['submit_upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['doc_title']);
    $year = (int)$_POST['report_year'];
    $desc = mysqli_real_escape_string($conn, $_POST['doc_description']);

    $target_dir = "../uploads/reports/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $file_name = time() . "_" . basename($_FILES["pdf_file"]["name"]);
    $db_path = "uploads/reports/" . $file_name;

    if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_dir . $file_name)) {
        mysqli_query($conn, "INSERT INTO annual_reports (title, report_year, description, pdf_path) VALUES ('$title', $year, '$desc', '$db_path')");
        header("Location: manage_annual_reports.php?success=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Annual Reports | Admin</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex !important; background: #f4f7f6 !important; margin: 0; padding: 0; text-align: left !important; width: 100%; min-height: 100vh; font-family: 'Poppins', sans-serif; }
        .admin-main { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        .admin-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        .form-group label { font-weight: 600; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-submit { background: #4175FC; color: white; border: none; padding: 12px 25px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: #062269; }
        
        /* Table Styling for existing reports */
        .report-list-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        .report-list-table th, .report-list-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .report-list-table th { background: #f8f9fa; font-weight: 600; }
        .delete-btn { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>
    
    <main class="admin-main">
        <?php if(isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Annual Report published successfully!
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <h2><i class="fas fa-file-pdf" style="color: #e74c3c;"></i> Post Annual Report</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Report Title</label>
                    <input type="text" name="doc_title" placeholder="e.g. 2024 Impact Report" required>
                </div>
                <div class="form-group">
                    <label>Fiscal Year</label>
                    <input type="number" name="report_year" value="2025" required>
                </div>
                <div class="form-group">
                    <label>Summary Description</label>
                    <textarea name="doc_description" rows="2" placeholder="Briefly describe the highlights..."></textarea>
                </div>
                <div class="form-group">
                    <label>Upload PDF</label>
                    <input type="file" name="pdf_file" accept=".pdf" required>
                </div>
                <button type="submit" name="submit_upload" class="btn-submit">Publish Annual Report</button>
            </form>
        </div>

        <h3>Existing Annual Reports</h3>
        <table class="report-list-table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Title</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM annual_reports ORDER BY report_year DESC");
                while($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><strong><?php echo $row['report_year']; ?></strong></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><a href="../<?php echo $row['pdf_path']; ?>" target="_blank">View PDF</a></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this report?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>