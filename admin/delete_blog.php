<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
include 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Get the image path first so we can delete the physical file
    $res = mysqli_query($conn, "SELECT image_path FROM blog_posts WHERE id = $id");
    $post = mysqli_fetch_assoc($res);

    if ($post) {
        // If an image exists, delete it from the uploads folder
        if (!empty($post['image_path']) && file_exists("../" . $post['image_path'])) {
            unlink("../" . $post['image_path']);
        }

        // 2. Delete the record from the database
        $sql = "DELETE FROM blog_posts WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            header("Location: manage_blog.php?deleted=1");
            exit;
        } else {
            echo "Error deleting post: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: manage_blog.php");
    exit;
}
?>