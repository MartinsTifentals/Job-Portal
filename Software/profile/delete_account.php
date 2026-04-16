<?php
// Job Portal file: profile\delete_account.php
session_start();
include "../includes/db.php";
include "../includes/header.php";

// Redirect unauthenticated users away from account deletion.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the current password before deleting the account.
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Remove the user's profile picture file, if one exists.
        $pic_stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id=?");
        $pic_stmt->bind_param("i", $user_id);
        $pic_stmt->execute();
        $pic_result = $pic_stmt->get_result()->fetch_assoc();

        if (!empty($pic_result['profile_picture'])) {
            $pic_path = "../assets/uploads/profile_pictures/" . $pic_result['profile_picture'];
            if (file_exists($pic_path)) {
                unlink($pic_path);
            }
        }

        // Delete the user record after verifying the password.
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();

        if ($delete_stmt->affected_rows > 0) {
            // End the session and redirect the user after successful deletion.
            session_destroy();
            header("Location: ../index.php?message=account_deleted");
            exit();
        } else {
            $error = "Failed to delete account.";
        }

    } else {
        $error = "Incorrect password!";
    }
}
?>

<style>
    .delete-container {
        max-width: 760px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #fee2e2;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .delete-title {
        margin: 0 0 14px 0;
        color: #111827;
        font-size: 2rem;
        font-weight: 700;
    }

    .delete-subtitle {
        margin: 0 0 26px 0;
        color: #6b7280;
        line-height: 1.6;
    }

    .warning-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        padding: 18px 20px;
        border-radius: 10px;
        margin: 22px 0;
        line-height: 1.6;
    }

    .error-box {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 14px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }

    .form-group input[type="password"] {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        box-sizing: border-box;
        font-size: 16px;
        transition: all 0.25s ease;
    }

    .form-group input[type="password"]:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 4px rgba(108, 43, 217, 0.1);
    }

    .confirm-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 25px 0;
        font-weight: 600;
        color: #b91c1c;
    }

    .confirm-row input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #dc2626;
    }

    .btn-row {
        display: flex;
        gap: 14px;
        margin-top: 24px;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
        padding: 14px 22px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.25s ease;
        flex: 1;
    }

    .btn-delete:hover {
        background: #b91c1c;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        padding: 14px 22px;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        text-align: center;
        border: 1px solid #d1d5db;
        flex: 1;
        transition: all 0.25s ease;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    @media (max-width: 768px) {
        .delete-container {
            margin: 30px 20px;
            padding: 26px;
        }

        .btn-row {
            flex-direction: column;
        }

        .btn-delete,
        .btn-cancel {
            width: 100%;
        }
    }
</style>

<div class="delete-container">
    <h2 class="delete-title">Delete Your Account</h2>
    <p class="delete-subtitle">This action is permanent and will remove your profile and all related records from the portal.</p>

    <div class="warning-box">
        <strong>This cannot be undone!</strong><br>
        Your profile, applications, CV, and all data will be permanently deleted.
    </div>

    <?php if ($error): ?>
        <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Enter your password to confirm:</label>
            <input type="password" name="password" required placeholder="Current password">
        </div>

        <div class="confirm-row">
            <input type="checkbox" name="confirm" required>
            <span>I confirm I want to permanently delete my account</span>
        </div>

        <div class="btn-row">
            <button type="submit" class="btn-delete">Delete Account</button>
            <a href="profile.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<?php include "../includes/footer.php"; ?>
