<?php
// Job Portal file: admin\review_job.php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: manage_jobs.php');
    exit;
}

$jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
$action = $_POST['action'] ?? '';

if ($jobId <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    $_SESSION['error'] = 'Invalid review request.';
    header('Location: manage_jobs.php');
    exit;
}

$newStatus = $action === 'approve' ? 'approved' : 'rejected';

$stmt = $conn->prepare("UPDATE jobs SET approval_status = ? WHERE id = ?");
if (!$stmt) {
    $_SESSION['error'] = 'Failed to prepare review query.';
    header('Location: manage_jobs.php');
    exit;
}

$stmt->bind_param("si", $newStatus, $jobId);

if ($stmt->execute()) {
    $_SESSION['success'] = $action === 'approve'
        ? 'Job approved successfully.'
        : 'Job rejected successfully.';
} else {
    $_SESSION['error'] = 'Failed to update job review status.';
}

$stmt->close();
header('Location: manage_jobs.php');
exit;


