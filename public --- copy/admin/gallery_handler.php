<?php
session_start();
include 'db.php';

if (isset($_POST['submit_gallery'])) {
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    
    // Set up folder paths
    $target_dir = "../uploads/gallery/"; 
    // Create folder if it doesn't exist
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $file_name = time() . "_" . basename($_FILES["gallery_img"]["name"]);
    $target_file = $target_dir . $file_name;
    $db_path = "uploads/gallery/" . $file_name;

    if (move_uploaded_file($_FILES["gallery_img"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO gallery (caption, image_path) VALUES ('$caption', '$db_path')";
        mysqli_query($conn, $sql);
        header("Location: manage_gallery.php?success=1");
    } else {
        echo "Upload failed.";
    }
}
?>