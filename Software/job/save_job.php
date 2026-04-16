<?php
// Job Portal file: job\save_job.php
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

if ($job_id <= 0) {
    header('Location: browse.php');
    exit;
}

// Ensure job exists
$jobCheck = $conn->prepare("SELECT id FROM jobs WHERE id = ?");
$jobCheck->bind_param("i", $job_id);
$jobCheck->execute();
$jobExists = $jobCheck->get_result()->fetch_assoc();
$jobCheck->close();

if (!$jobExists) {
    header('Location: browse.php');
    exit;
}

// Ensure saved_jobs table exists (safe no-op if already present)
$conn->query("
    CREATE TABLE IF NOT EXISTS saved_jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_id INT NOT NULL,
        saved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_saved_job (user_id, job_id)
    )
");

// Avoid duplicates
$check = $conn->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
$check->bind_param("ii", $user_id, $job_id);
$check->execute();
$existing = $check->get_result()->fetch_assoc();
$check->close();

if ($existing) {
    header("Location: job_details.php?id={$job_id}&info=already_saved");
    exit;
}

$insert = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
$insert->bind_param("ii", $user_id, $job_id);
$insert->execute();
$insert->close();

header("Location: job_details.php?id={$job_id}&success=saved");
exit;
?>

