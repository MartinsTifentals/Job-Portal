<?php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

$users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$jobs = $conn->query("SELECT COUNT(*) as total FROM jobs")->fetch_assoc()['total'];
$promoted = $conn->query("SELECT COUNT(*) as total FROM jobs WHERE is_promoted = 1")->fetch_assoc()['total'];
?>

<link rel="stylesheet" href="\Job_Portal\assets\css\admin.css">

<div class="admin-container">
    <h1>Admin Dashboard</h1>
    <div class="admin-stats">
        <div class="stat-card">
            <h3><?php echo $users; ?></h3>
            <p>Total Users</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $jobs; ?></h3>
            <p>Total Jobs</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $promoted; ?></h3>
            <p>Promoted Jobs</p>
        </div>
    </div>
    <div class="admin-links">
        <a href="manage_jobs.php">Manage Jobs</a>
        <a href="manage_users.php">Manage Users</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>