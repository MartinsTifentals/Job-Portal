<?php
require_once "../includes/auth_check.php";
require_once "../includes/admin_check.php";
require_once "../includes/db.php";

if (isset($_GET['id']) && isset($_GET['value'])) {
    $id = intval($_GET['id']);
    $value = intval($_GET['value']);

    $stmt = $conn->prepare("UPDATE jobs SET is_promoted=? WHERE id=?");
    $stmt->bind_param("ii", $value, $id);
    $stmt->execute();
}

header("Location: manage_jobs.php");
exit();