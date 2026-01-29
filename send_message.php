<?php
// 1. Connect to the database
include 'admin/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Capture and clean the data
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // 3. Insert into the 'messages' table
    // We set status to 'unread' by default
    $sql = "INSERT INTO messages (name, email, subject, message, status) 
            VALUES ('$name', '$email', '$subject', '$message', 'unread')";

    if (mysqli_query($conn, $sql)) {
        // Success! Redirect back with a success message
        header("Location: contact.php?success=1");
        exit();
    } else {
        // Error! Show what went wrong
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If someone tries to access this file directly, send them back
    header("Location: contact.php");
    exit();
}
?>