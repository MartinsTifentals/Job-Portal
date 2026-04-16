<?php
// Job Portal file: admin\delete_job.php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error'] = 'Invalid job ID';
    header("Location: manage_jobs.php");
    exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Job deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete job';
    }
} catch (Exception $e) {
    error_log("Delete job error: " . $e->getMessage());
    $_SESSION['error'] = 'Database error occurred';
}

header("Location: manage_jobs.php");
exit();
?>

