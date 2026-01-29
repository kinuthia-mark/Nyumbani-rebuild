<?php
session_start();
include 'db.php';

// Check if user is logged in before processing
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_gallery'])) {
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    
    // 1. Capture the specific user name from the session (David or Mark)
    $uploader = $_SESSION['admin_name']; 
    
    // Set up folder paths
    $target_dir = "../uploads/gallery/"; 
    
    // Create folder if it doesn't exist
    if (!file_exists($target_dir)) { 
        mkdir($target_dir, 0777, true); 
    }

    $file_name = time() . "_" . basename($_FILES["gallery_img"]["name"]);
    $target_file = $target_dir . $file_name;
    $db_path = "uploads/gallery/" . $file_name;

    if (move_uploaded_file($_FILES["gallery_img"]["tmp_name"], $target_file)) {
        // 2. Updated SQL to include the 'uploaded_by' column
        $sql = "INSERT INTO gallery (caption, image_path, uploaded_by) VALUES ('$caption', '$db_path', '$uploader')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: manage_gallery.php?success=1");
            exit;
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Upload failed.";
    }
}
?>