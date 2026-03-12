<?php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM jobs WHERE id=$id");

header("Location: manage_jobs.php");
exit();
?>