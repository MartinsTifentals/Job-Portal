<?php
include '../includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat-password'];

    if (empty($name) || empty($email) || empty($password) || empty($repeatPassword)) {
        $message = "All fields are required.";
    } elseif ($password !== $repeatPassword) {
        $message = "Passwords do not match.";
    } else {

        $check = "SELECT id FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) > 0) {
            $message = "Email already registered.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (name, email, password) 
                      VALUES ('$name', '$email', '$hashed_password')";
            // change the index to wherever we want them to be redirected after logging in
            if (mysqli_query($conn, $query)) {
                header("Location: ../index.php");
                exit();
            } else {
                $message = "Something went wrong.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Signup</title>
    
</head>
<body id="signup">
    <?php include "../includes/header.php" ?>
    <div class="auth-container">
        <div class="wrapper">
            <h1>Create Account</h1>
            <p class="subtitle">Join JobMatrix and start applying</p>

            <?php if (!empty($message)): ?>
                <p class="error-message">
                    <?php echo $message; ?>
                </p>
            <?php endif; ?>

            <form action="signup.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>

                <input type="email" name="email" placeholder="Email Address" required>

                <input type="password" name="password" placeholder="Password" required>

                <input type="password" name="repeat-password" placeholder="Repeat Password" required>

                <button type="submit">Sign Up</button>
            </form>

            <div class="auth-footer">
                Already have an account?
                <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>