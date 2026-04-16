<?php
// Job Portal file: profile\delete_cv.php
session_start();
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT cv_file FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user['cv_file'] && file_exists('../' . $user['cv_file'])) {
    unlink('../' . $user['cv_file']);
}

$stmt = $conn->prepare("UPDATE users SET cv_file = NULL WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

header("Location: profile.php?cv=deleted");
exit();
?>

