<?php
session_start();
include 'db.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Get the file path so we can delete the actual file from the folder
    $res = mysqli_query($conn, "SELECT image_path FROM gallery WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    unlink("../" . $row['image_path']); // This deletes the actual file

    // 2. Delete the record from the database
    mysqli_query($conn, "DELETE FROM gallery WHERE id = $id");
    
    header("Location: manage_gallery.php");
}
?>