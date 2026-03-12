<?php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="\Job_Portal\assets\css\admin.css">

<div class="admin-container">
    <h1>Manage Users</h1>
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <a class="edit-btn" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>