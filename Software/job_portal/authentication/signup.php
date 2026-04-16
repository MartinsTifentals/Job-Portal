<?php
// Job Portal file: authentication\signup.php
include '../includes/db.php';
include '../notifmt.php';
include '../emailer.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat-password'];
    $allowedRoles = ['user', 'employer'];
    $selectedRole = isset($_POST['role']) ? trim($_POST['role']) : 'user';

    if (!in_array($selectedRole, $allowedRoles, true)) {
        $selectedRole = 'user';
    }

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

            $onboardingCompletedValue = ($selectedRole === 'employer') ? 'TRUE' : 'FALSE';

            $query = "INSERT INTO users (name, email, password, onboarding_completed, role, created_at) 
                      VALUES ('$name', '$email', '$hashed_password', $onboardingCompletedValue, '$selectedRole', NOW())";

            if (mysqli_query($conn, $query)) {
                $user_id = mysqli_insert_id($conn);
                session_start();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_role'] = $selectedRole;
                $_SESSION['role'] = $selectedRole;

                if ($selectedRole === 'employer') {
                    header("Location: ../employers/post_job.php");
                } else {
                    $emailNotfier = new EmailNotifier();
                    $emailNotfier->sendSignupEmail($email, $name);
                    header("Location: ../profile/onboarding.php");
                    OnScreenNotifier::addNotification('success', 'Signup Successful', 'Your account has been created successfully.');
                }
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Signup - JobMatrix</title>
    <style>
        body.modal-open,
        html.modal-open {
            overflow: hidden !important;
            height: 100vh !important;
        }

        body.modal-open {
            position: fixed;
            width: 100%;
        }

        .role-toggle-group {
            margin-top: 10px;
            margin-bottom: 8px;
        }

        .role-toggle-label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .role-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .role-toggle-buttons .role-option {
            margin-top: 0 !important;
            width: 100%;
            border: 1px solid #7c3aed;
            border-radius: 8px;
            padding: 10px 12px;
            font-weight: 600;
            background: #fff;
            color: #5b21b6;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: none;
        }

        .role-toggle-buttons .role-option:hover {
            border-color: #8b5cf6;
            background: #fff;
        }

        .role-toggle-buttons .role-option.active {
            background: #7c3aed !important;
            border-color: #7c3aed !important;
            color: #fff !important;
            box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.15);
        }
    </style>
</head>

<body id="signup">
    <?php include "../includes/header.php"; ?>

    <!-- LOGIN MODAL -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModal">X</button>
            <h3>Sign In To Job Matrix</h3>
            <p class="modal-subtitle">Welcome Back! Please Login to Your Account</p>

            <?php if (isset($_GET['error'])): ?>
                <p style="color:red; text-align:center;">
                    <?php

                    if ($_GET['error'] == 'wrongpassword') {
                        echo "Incorrect password.";
                    }
                    if ($_GET['error'] == 'nouser') {
                        echo "User not found.";
                    }

                    ?>
                </p>
            <?php endif; ?>
            <form class="login-form" method="POST" action="login.php">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email address" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <button type="submit" class="continue-btn">Login</button>
            </form>

            <p class="modal-footer-text">
                Don't Have An Account? <a href="signup.php">Sign Up</a>
            </p>
        </div>
    </div>

    <div class="auth-container">
        <div class="wrapper">
            <h1>Create Account</h1>
            <p class="subtitle">Join JobMatrix and start applying</p>

            <?php if (!empty($message)): ?>
                <p class="error-message"><?php echo $message; ?></p>
            <?php endif; ?>

            <form action="signup.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="repeat-password" placeholder="Repeat Password" required>

                <div class="role-toggle-group">
                    <label class="role-toggle-label">Sign up as</label>
                    <input type="hidden" name="role" id="roleInput" value="user">
                    <div class="role-toggle role-toggle-buttons">
                        <button type="button" class="role-option active" data-role="user">User</button>
                        <button type="button" class="role-option" data-role="employer">Employer</button>
                    </div>
                </div>

                <button type="submit">Sign Up</button>
            </form>

            <div class="auth-footer">
                Already have an account?
                <a href="#" id="loginBtn">Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginButtons = document.querySelectorAll('#loginBtn');
            const modal = document.getElementById('loginModal');
            const closeModalBtn = document.getElementById('closeModal');

            loginButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    modal.classList.add('active');
                    document.body.classList.add('modal-open');
                    document.documentElement.classList.add('modal-open');
                });
            });

            // Close modal function
            const closeModal = () => {
                modal.classList.remove('active');
                document.body.classList.remove('modal-open');
                document.documentElement.classList.remove('modal-open');
            };
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }
            // Close on outside click
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
            // ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) {
                    closeModal();
                }
            });

            // Role toggle
            const roleInput = document.getElementById('roleInput');
            const roleOptions = document.querySelectorAll('.role-option');

            roleOptions.forEach(option => {
                option.addEventListener('click', function () {
                    const selectedRole = this.getAttribute('data-role');
                    roleInput.value = selectedRole;

                    roleOptions.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>
