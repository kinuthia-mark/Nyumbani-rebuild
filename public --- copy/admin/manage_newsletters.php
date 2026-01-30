<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
include 'db.php';

// --- HANDLE UPLOAD ---
if (isset($_POST['upload_news'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $p_date = $_POST['publish_date'];

    $target_dir = "../uploads/newsletters/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    // Handle PDF
    $pdf_name = time() . "_doc_" . basename($_FILES['pdf_file']['name']);
    move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_dir . $pdf_name);
    $pdf_db = "uploads/newsletters/" . $pdf_name;

    // Handle Thumbnail
    $thumb_name = time() . "_thumb_" . basename($_FILES['thumb_file']['name']);
    move_uploaded_file($_FILES['thumb_file']['tmp_name'], $target_dir . $thumb_name);
    $thumb_db = "uploads/newsletters/" . $thumb_name;

    mysqli_query($conn, "INSERT INTO newsletters (title, description, publish_date, pdf_path, thumbnail_path) 
                         VALUES ('$title', '$desc', '$p_date', '$pdf_db', '$thumb_db')");
    header("Location: manage_newsletters.php?success=1");
    exit;
}

// --- HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = mysqli_query($conn, "SELECT pdf_path, thumbnail_path FROM newsletters WHERE id = $id");
    $files = mysqli_fetch_assoc($res);
    if ($files) {
        unlink("../" . $files['pdf_path']);
        unlink("../" . $files['thumbnail_path']);
    }
    mysqli_query($conn, "DELETE FROM newsletters WHERE id = $id");
    header("Location: manage_newsletters.php?deleted=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Newsletters | Nyumbani Admin</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex !important; background: #f4f7f6 !important; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        .admin-main { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        
        .admin-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 900px; margin-bottom: 30px; }
        .admin-card h2 { margin-top: 0; color: #062269; display: flex; align-items: center; gap: 10px; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        .form-group.full { grid-column: span 2; }
        
        label { font-weight: 600; color: #333; font-size: 14px; }
        input, textarea, select { padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; }
        
        .btn-publish { background: #4175FC; color: white; border: none; padding: 15px 30px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-publish:hover { background: #062269; }

        /* Listing Table */
        .news-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .news-table th, .news-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .news-table th { background: #062269; color: white; }
        .thumb-preview { width: 50px; height: 60px; object-fit: cover; border-radius: 4px; }
        .delete-btn { color: #e74c3c; cursor: pointer; text-decoration: none; }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-card">
            <h2><i class="fas fa-newspaper"></i> Upload Newsletter</h2>
            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <div class="form-group full">
                    <label>Newsletter Title</label>
                    <input type="text" name="title" placeholder="e.g. Hope in the Village - August 2024" required>
                </div>
                
                <div class="form-group full">
                    <label>Short Description</label>
                    <textarea name="description" rows="2" placeholder="Brief summary of the news..."></textarea>
                </div>

                <div class="form-group">
                    <label>Publishing Date</label>
                    <input type="date" name="publish_date" required>
                </div>

                <div class="form-group">
                    <label>PDF Document</label>
                    <input type="file" name="pdf_file" accept=".pdf" required>
                </div>

                <div class="form-group">
                    <label>Cover Image (Thumbnail)</label>
                    <input type="file" name="thumb_file" accept="image/*" required>
                </div>

                <div class="form-group" style="justify-content: flex-end;">
                    <button type="submit" name="upload_news" class="btn-publish">Publish Newsletter</button>
                </div>
            </form>
        </div>

        <h3 style="color: #062269;">Recent Uploads</h3>
        <table class="news-table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM newsletters ORDER BY publish_date DESC");
                while($news = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="../<?php echo $news['thumbnail_path']; ?>" class="thumb-preview"></td>
                    <td><strong><?php echo htmlspecialchars($news['title']); ?></strong></td>
                    <td><?php echo date('M d, Y', strtotime($news['publish_date'])); ?></td>
                    <td>
                        <a href="?delete=<?php echo $news['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

</body>
</html>