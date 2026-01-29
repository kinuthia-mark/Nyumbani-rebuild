<?php
session_start();
include 'db.php'; // Ensure this file contains: $conn = mysqli_connect(...);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input
    $user_input = mysqli_real_escape_string($conn, $_POST['username']);
    $pass_input = $_POST['password'];

    // 2. Query your table (using 'username' as per your structure)
    $query = "SELECT * FROM users WHERE username = '$user_input' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // 3. Compare plain text password 
        // Note: Change this to password_verify() once you start hashing
        if ($pass_input === $user['password']) {
            
            // --- SET SESSIONS FOR SIDEBAR ---
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            
            // We save 'username' into the session so the sidebar can see it
            $_SESSION['admin_name'] = $user['username']; 

            // Redirect to the dashboard/messages page
            header("Location: manage_messages.php");
            exit();

        } else {
            // Wrong password
            header("Location: login.php?error=wrong_password");
            exit();
        }
    } else {
        // Username doesn't exist
        header("Location: login.php?error=user_not_found");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>