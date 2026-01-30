<?php
session_start();
include 'db.php';

if (isset($_POST['submit_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['blog_title']);
    $content = mysqli_real_escape_string($conn, $_POST['blog_content']);
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

    $sql = "INSERT INTO blog_posts (title, content, image_path) VALUES ('$title', '$content', '$db_path')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_blog.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>