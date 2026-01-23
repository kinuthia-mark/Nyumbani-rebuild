<?php
session_start();
include 'db.php'; // Ensure this file exists with your MySQL connection info

if (isset($_POST['login_submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = $_POST['pass'];

    // Query to find the user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Simple password check (for better security later, use password_verify)
        if ($password === $row['password']) {
            // SUCCESS: Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $row['username'];
            
            header("Location: admin.php");
            exit;
        }
    }
    
    // FAIL: Go back to login with error
    header("Location: login.php?error=1");
    exit;
}
?>