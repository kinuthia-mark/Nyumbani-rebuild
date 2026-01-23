<?php
session_start();
include '../db.php'; 

if (isset($_POST['submit_upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['doc_title']);
    $category = mysqli_real_escape_string($conn, $_POST['doc_category']);
    $description = mysqli_real_escape_string($conn, $_POST['doc_description']);
    
    // File upload settings
    $target_dir = "../../uploads/"; // Go up two levels to find the uploads folder
    $file_name = time() . "_" . basename($_FILES["pdf_file"]["name"]); // Add timestamp to avoid duplicate names
    $target_file = $target_dir . $file_name;
    $db_path = "uploads/" . $file_name; // This is the path we save in the database

    // Check if the file is a PDF
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($fileType != "pdf") {
        die("Sorry, only PDF files are allowed.");
    }

    // Move the file from temporary storage to your uploads folder
    if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
        
        // Insert info into the database
        $sql = "INSERT INTO documents (title, category, description, file_path) 
                VALUES ('$title', '$category', '$description', '$db_path')";

        if (mysqli_query($conn, $sql)) {
            header("Location: admin.php?success=1");
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file. Check if the 'uploads' folder exists.";
    }
}
?>