<?php
include_once "auth_check.php";
include_once "db.php";

$user_id = $_SESSION['user_id'];

$query = "SELECT role FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($user['role'] !== 'admin') {
    header("Location: /Job_Portal/index.php");
    exit();
}
?>