<?php
session_start();
include 'db.php';

// Safety check to ensure only logged-in admins can post
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['blog_title']);
    $content = mysqli_real_escape_string($conn, $_POST['blog_content']);
    
    // 1. Capture the admin's name (David or Mark) from the session
    $uploader = $_SESSION['admin_name']; 
    
    // Catch the status from the form
    $status = isset($_POST['blog_status']) ? mysqli_real_escape_string($conn, $_POST['blog_status']) : 'published';
    
    $db_path = ""; // Default if no image is uploaded

    // Handle Image Upload if provided
    if (!empty($_FILES["blog_img"]["name"])) {
        $target_dir = "../uploads/blog/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $file_name = time() . "_" . basename($_FILES["blog_img"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["blog_img"]["tmp_name"], $target_file)) {
            $db_path = "uploads/blog/" . $file_name;
        }
    }

    // 2. UPDATED SQL: Now includes the 'uploaded_by' column
    $sql = "INSERT INTO blog_posts (title, content, image_path, status, uploaded_by) 
            VALUES ('$title', '$content', '$db_path', '$status', '$uploader')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_blog.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>