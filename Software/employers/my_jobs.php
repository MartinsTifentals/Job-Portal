<?php
// Job Portal file: employers\my_jobs.php
include "../includes/employer_check.php";
include "../includes/header.php";
include "../includes/db.php";

$currentUserId = (int)$_SESSION['user_id'];
$currentUserRole = 'user';

$roleQuery = mysqli_query($conn, "SELECT role FROM users WHERE id = $currentUserId LIMIT 1");
if ($roleQuery && mysqli_num_rows($roleQuery) > 0) {
    $roleRow = mysqli_fetch_assoc($roleQuery);
    $currentUserRole = $roleRow['role'] ?? 'user';
}

$ownerColumnExists = false;
$ownerColumnCheck = mysqli_query($conn, "SHOW COLUMNS FROM jobs LIKE 'employer_id'");
if ($ownerColumnCheck && mysqli_num_rows($ownerColumnCheck) > 0) {
    $ownerColumnExists = true;
}

$jobs = [];

if ($currentUserRole === 'admin') {
    $jobResult = $conn->query("
        SELECT id, title, company, location, type, category, is_promoted, created_at
        FROM jobs
        ORDER BY created_at DESC
    ");
} else {
    if ($ownerColumnExists) {
        $stmt = $conn->prepare("
            SELECT id, title, company, location, type, category, is_promoted, created_at
            FROM jobs
            WHERE employer_id = ?
            ORDER BY created_at DESC
        ");
        if ($stmt) {
            $stmt->bind_param("i", $currentUserId);
            $stmt->execute();
            $jobResult = $stmt->get_result();
        } else {
            $jobResult = false;
        }
    } else {
        $jobResult = false;
    }
}

if ($jobResult) {
    while ($row = $jobResult->fetch_assoc()) {
        $jobs[] = $row;
    }
}
?>

<style>
    .my-jobs-page {
        max-width: 1180px;
        margin: 42px auto 80px;
        padding: 0 16px;
    }

    .my-jobs-hero {
        background: linear-gradient(135deg, #6c2bd9, #8a5cff);
        border-radius: 16px;
        color: #fff;
        padding: 32px 28px;
        margin-bottom: 22px;
        box-shadow: 0 14px 30px rgba(69, 31, 145, 0.25);
    }

    .my-jobs-hero h1 {
        margin: 0 0 8px;
        font-size: 2rem;
    }

    .my-jobs-hero p {
        margin: 0;
        opacity: 0.95;
        max-width: 820px;
    }

    .jobs-panel {
        background: #fff;
        border: 1px solid #ececf3;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(20, 20, 40, 0.04);
    }

    .jobs-grid {
        display: grid;
        gap: 14px;
    }

    .job-card {
        border: 1px solid #ece9fb;
        background: #faf9ff;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .job-meta h3 {
        margin: 0 0 6px;
        color: #2b2252;
        font-size: 1.05rem;
    }

    .job-meta p {
        margin: 0;
        color: #555;
        font-size: 0.92rem;
    }

    .job-tags {
        margin-top: 6px;
        font-size: 0.85rem;
        color: #6a30d6;
        font-weight: 600;
    }

    .job-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-promote {
        text-decoration: none;
        border: none;
        background: #6128cf;
        color: #fff;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
        display: inline-block;
    }

    .btn-promote:hover {
        background: #4f1fb0;
        transform: translateY(-1px);
    }

    .empty-state {
        color: #666;
        font-size: 0.95rem;
        padding: 8px 2px;
    }
</style>

<div class="my-jobs-page">
    <section class="my-jobs-hero">
        <h1>My Jobs</h1>
        <p>View your posted jobs and quickly promote any listing.</p>
    </section>

    <section class="jobs-panel">
        <?php if (empty($jobs)): ?>
            <p class="empty-state">No jobs found.</p>
        <?php else: ?>
            <div class="jobs-grid">
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <div class="job-meta">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p>
                                <?php echo htmlspecialchars($job['company']); ?> â€”
                                <?php echo htmlspecialchars($job['location']); ?>
                            </p>
                            <div class="job-tags">
                                <?php echo htmlspecialchars($job['type']); ?> Â·
                                <?php echo htmlspecialchars($job['category']); ?>
                                <?php if ((int)$job['is_promoted'] === 1): ?>
                                    Â· Promoted
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="job-actions">
                            <a class="btn-promote" href="/Job_Portal/employers/promote.php?job_id=<?php echo (int)$job['id']; ?>">Promote</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include "../includes/footer.php"; ?>

