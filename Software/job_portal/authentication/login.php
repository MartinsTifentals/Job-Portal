<?php
// Job Portal file: authentication\login.php
session_start();

// Database connection and notification helper are required for login validation
include '../includes/db.php';
include '../notifmt.php';

// Only process login when the form submits via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize email input and keep password raw for verification.
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Look up the user by email to verify credentials.
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Check the supplied password against the stored hash.
        if (password_verify($password, $user['password'])) {

            // Store authenticated user data in the session.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../index.php");
            OnScreenNotifier::addNotification('success', 'Login Successful', 'You have been logged in successfully.');
            exit();

        } else {
            // Wrong password, redirect with an error code.
            header("Location: ../index.php?error=wrongpassword");
            exit();
        }
    } else {
        // No user matches the provided email address.
        header("Location: ../index.php?error=nouser");
        exit();
    }
}


