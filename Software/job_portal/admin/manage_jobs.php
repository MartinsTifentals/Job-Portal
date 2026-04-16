<?php
// Job Portal file: admin\manage_jobs.php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$stmt = $conn->prepare("SELECT * FROM jobs ORDER BY id ASC");
$stmt->execute();
$jobs = $stmt->get_result();
$total_jobs = $jobs->num_rows;
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
        <h1>Manage Jobs (<?= number_format($total_jobs) ?> total)</h1>
        <a href="dashboard.php" class="btn btn-secondary">â† Dashboard</a>
    </div>

    <?php if ($jobs->num_rows > 0): ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Category</th>
                        <th>Promotion</th>
                        <th>Approval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($job = $jobs->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?= $job['id'] ?></strong></td>
                            <td>
                                <?= htmlspecialchars($job['title']) ?>
                                <?php if ($job['is_promoted']): ?>
                                    <span class="status-badge status-promoted">Promoted</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($job['location']) ?></td>
                            <td><?= htmlspecialchars($job['category']) ?></td>
                            <td>
                                <span class="status-badge <?= $job['is_promoted'] ? 'status-promoted' : 'status-normal' ?>">
                                    <?= $job['is_promoted'] ? 'Promoted' : 'Normal' ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $approvalStatus = $job['approval_status'] ?? 'pending';
                                $approvalClass = 'status-normal';
                                if ($approvalStatus === 'approved') {
                                    $approvalClass = 'status-promoted';
                                } elseif ($approvalStatus === 'rejected') {
                                    $approvalClass = 'status-danger';
                                }
                                ?>
                                <span class="status-badge <?= $approvalClass ?>">
                                    <?= ucfirst(htmlspecialchars($approvalStatus)) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if (($job['approval_status'] ?? 'pending') !== 'approved'): ?>
                                        <form method="POST" action="review_job.php" style="display:inline;">
                                            <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-edit" title="Approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if (($job['approval_status'] ?? 'pending') !== 'rejected'): ?>
                                        <form method="POST" action="review_job.php" style="display:inline;">
                                            <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-delete" title="Reject"
                                                onclick="return confirm('Reject this job listing?');">
                                                <i class="fas fa-ban"></i> Reject
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <a href="edit_job.php?id=<?= $job['id'] ?>" class="btn btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_job.php?id=<?= $job['id'] ?>" class="btn btn-delete"
                                        onclick="return confirm('Delete this job? This cannot be undone.')" title="Delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>No jobs yet</h3>
            <p>Jobs will appear here once employers start posting.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
