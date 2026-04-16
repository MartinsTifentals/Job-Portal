<?php
// Job Portal file: admin\manage_users.php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

$stmt = $conn->prepare("SELECT id, name, email, created_at, onboarding_completed FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->get_result();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<link rel="stylesheet" href="../assets/css/admin.css">

<div class="admin-container">
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="admin-header">
        <h1>Manage Users (<?= $users->num_rows ?> total)</h1>
        <a href="dashboard.php" class="btn btn-secondary">â† Back to Dashboard</a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Onboarding</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?= $user['id'] ?></strong></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['onboarding_completed']): ?>
                                <span class="status-badge status-complete">Complete</span>
                            <?php else: ?>
                                <span class="status-badge status-pending">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-delete"
                                    onclick="return confirm('Delete <?= htmlspecialchars($user['name']) ?>? This cannot be undone.')"
                                    title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if ($users->num_rows === 0): ?>
        <div class="empty-state">
            <h3>No users yet</h3>
            <p>Your first users will appear here after they sign up.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
