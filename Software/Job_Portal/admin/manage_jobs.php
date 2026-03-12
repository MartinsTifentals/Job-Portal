<?php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

$jobs = $conn->query("SELECT * FROM jobs ORDER BY id ASC");
?>

<link rel="stylesheet" href="\Job_Portal\assets\css\admin.css">

<div class="admin-container">
    <h1>Manage Jobs</h1>
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($job = $jobs->fetch_assoc()): ?>
            <tr>
                <td><?php echo $job['id']; ?></td>
                <td>
                    <?php echo $job['title']; ?>
                    <?php if ($job['is_promoted']): ?>
                        <span class="badge">Promoted</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $job['location']; ?></td>
                <td>
                    <?php echo $job['is_promoted'] ? "Promoted" : "Normal"; ?>
                </td>
                <td>
                    <a class="edit-btn" href="edit_job.php?id=<?php echo $job['id']; ?>">Edit</a>
                    <a class="delete-btn" href="delete_job.php?id=<?php echo $job['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>