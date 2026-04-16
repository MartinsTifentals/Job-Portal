<?php
// Job Portal file: job\remove_saved_job.php
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$job_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($job_id > 0) {
    $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: my_applications.php?success=saved_removed');
exit;
?>


