<?php
// Job Portal file: admin\delete_user.php
session_start();
include "../includes/auth_check.php";
include "../includes/admin_check.php";
include "../includes/db.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error'] = 'Invalid user ID';
    header("Location: manage_users.php");
    exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'User deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete user';
    }
} catch (Exception $e) {
    error_log("Delete user error: " . $e->getMessage());
    $_SESSION['error'] = 'Database error occurred';
}

header("Location: manage_users.php");
exit();
?>

