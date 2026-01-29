<?php
session_start();
include 'db.php'; // Ensure this file exists with your MySQL connection info ($conn)

if (isset($_POST['login_submit'])) {
    // 1. Sanitize the username input
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = $_POST['pass'];

    // 2. Query to find the user in the 'users' table
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // 3. Check if user exists
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // 4. Password check (Direct comparison for your plain-text database)
        if ($password === $row['password']) {
            
            // SUCCESS: Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            
            // We use 'admin_name' here so the sidebar can find it
            $_SESSION['admin_name'] = $row['username'];
            
            // Redirect to the admin dashboard
            header("Location: admin.php");
            exit();
            
        } else {
            // Wrong password
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        // User not found
        header("Location: login.php?error=1");
        exit();
    }
} else {
    // If someone tries to access this file without submitting the form
    header("Location: login.php");
    exit();
}
?>