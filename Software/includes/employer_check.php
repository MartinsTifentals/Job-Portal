<?php
// Job Portal file: includes\employer_check.php
include_once "auth_check.php";
include_once "db.php";

$user_id = (int) $_SESSION['user_id'];

$query = "SELECT role FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: /Job_Portal/index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
$role = $user['role'] ?? '';

if ($role !== 'admin' && $role !== 'employer') {
    header("Location: /Job_Portal/index.php");
    exit();
}
?>


