<?php
// Job Portal file: job\my_applications.php
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// Ensure tables exist
$conn->query("
    CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_id INT NOT NULL,
        status VARCHAR(50) DEFAULT 'applied',
        cover_letter TEXT NULL,
        applied_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_job (user_id, job_id)
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS saved_jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_id INT NOT NULL,
        saved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_saved_job (user_id, job_id)
    )
");

$appliedSql = "
    SELECT a.applied_at, a.status, j.id AS job_id, j.title, j.company, j.location, j.type
    FROM applications a
    INNER JOIN jobs j ON j.id = a.job_id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC
";
$appliedStmt = $conn->prepare($appliedSql);
$appliedStmt->bind_param("i", $user_id);
$appliedStmt->execute();
$appliedResult = $appliedStmt->get_result();

$savedSql = "
    SELECT s.saved_at, j.id AS job_id, j.title, j.company, j.location, j.type
    FROM saved_jobs s
    INNER JOIN jobs j ON j.id = s.job_id
    WHERE s.user_id = ?
    ORDER BY s.saved_at DESC
";
$savedStmt = $conn->prepare($savedSql);
$savedStmt->bind_param("i", $user_id);
$savedStmt->execute();
$savedResult = $savedStmt->get_result();
?>

<style>
  .apps-shell {
    width: 40vw;
    min-width: 900px;
    max-width: 1400px;
    margin: 60px auto;
    padding: 40px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid #ececf2;
  }

  .apps-header {
    display: flex;
    justify-content: space-between;
    align-items: end;
    gap: 16px;
    margin-bottom: 28px;
    padding-bottom: 18px;
    border-bottom: 2px solid #f3f4f6;
  }

  .apps-title {
    margin: 0;
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
  }

  .apps-subtitle {
    margin: 8px 0 0;
    color: #667085;
    font-size: 0.96rem;
  }

  .apps-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
  }

  .apps-card {
    background: linear-gradient(180deg, #ffffff 0%, #fcfcff 100%);
    border: 1px solid #e7e9f2;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 6px 24px rgba(15, 23, 42, 0.06);
  }

  .apps-card h3 {
    margin: 0 0 14px;
    font-size: 1.08rem;
    color: #111827;
    font-weight: 700;
  }

  .job-item {
    border: 1px solid #eceff6;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 12px;
    background: #ffffff;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
  }

  .job-item:hover {
    box-shadow: 0 8px 18px rgba(17, 24, 39, 0.08);
    transform: translateY(-1px);
  }

  .job-item:last-child {
    margin-bottom: 0;
  }

  .job-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
  }

  .job-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: #101828;
  }

  .job-meta {
    margin: 0 0 6px;
    color: #667085;
    font-size: 0.92rem;
    line-height: 1.45;
  }

  .job-actions {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 8px;
  }

  .job-actions a {
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    color: #720963;
  }

  .job-actions a:hover {
    text-decoration: underline;
  }

  .inline-form {
    display: inline;
    margin: 0;
  }

  .link-btn {
    border: none;
    background: none;
    color: #b42318;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
  }

  .link-btn:hover {
    text-decoration: underline;
  }

  .muted {
    color: #6b7280;
    margin: 0;
    font-size: 0.94rem;
  }

  .badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 700;
    background: #f3e8ff;
    color: #6b21a8;
    border-radius: 999px;
    padding: 4px 10px;
    white-space: nowrap;
  }

  .flash {
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 16px;
    font-size: 0.92rem;
    font-weight: 600;
  }

  .flash-success {
    background: #ecfdf3;
    border: 1px solid #abefc6;
    color: #067647;
  }

  @media (max-width: 1100px) {
    .apps-shell {
      width: auto;
      min-width: 0;
      margin: 30px 18px;
      padding: 24px;
    }

    .apps-grid {
      grid-template-columns: 1fr;
    }

    .apps-title {
      font-size: 1.7rem;
    }
  }

  @media (max-width: 640px) {
    .apps-shell {
      margin: 20px 12px;
      padding: 16px;
      border-radius: 12px;
    }

    .apps-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
      margin-bottom: 18px;
      padding-bottom: 14px;
    }

    .apps-title {
      font-size: 1.4rem;
    }

    .apps-card {
      padding: 14px;
    }

    .job-item {
      padding: 12px;
    }

    .job-top {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>

<div class="apps-shell">
  <div class="apps-header">
    <div>
      <h2 class="apps-title">My Applications</h2>
      <p class="apps-subtitle">Track your applied jobs and manage saved opportunities in one place.</p>
    </div>
  </div>

  <?php if (isset($_GET['success']) && $_GET['success'] === 'application_removed'): ?>
    <div class="flash flash-success">Application removed successfully.</div>
  <?php elseif (isset($_GET['success']) && $_GET['success'] === 'saved_removed'): ?>
    <div class="flash flash-success">Saved job removed successfully.</div>
  <?php endif; ?>

  <div class="apps-grid">
    <section class="apps-card">
      <h3>Applied Jobs</h3>
      <?php if ($appliedResult && $appliedResult->num_rows > 0): ?>
        <?php while ($row = $appliedResult->fetch_assoc()): ?>
          <article class="job-item">
            <div class="job-top">
              <p class="job-title"><?= htmlspecialchars($row['title']) ?></p>
              <span class="badge"><?= htmlspecialchars(ucfirst($row['status'] ?: 'applied')) ?></span>
            </div>
            <p class="job-meta">
              <?= htmlspecialchars($row['company']) ?> â€¢ <?= htmlspecialchars($row['location']) ?>
              <?php if (!empty($row['type'])): ?> â€¢ <?= htmlspecialchars($row['type']) ?><?php endif; ?>
            </p>
            <p class="job-meta">Applied on: <?= htmlspecialchars(date('d M Y, H:i', strtotime($row['applied_at']))) ?></p>
            <div class="job-actions">
              <a href="job_details.php?id=<?= (int) $row['job_id'] ?>">View job details</a>
              <form class="inline-form" method="POST" action="remove_application.php?id=<?= (int) $row['job_id'] ?>" onsubmit="return confirm('Remove this application?');">
                <button type="submit" class="link-btn">Remove application</button>
              </form>
            </div>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="muted">You have not applied to any jobs yet.</p>
      <?php endif; ?>
    </section>

    <section class="apps-card">
      <h3>Saved Jobs</h3>
      <?php if ($savedResult && $savedResult->num_rows > 0): ?>
        <?php while ($row = $savedResult->fetch_assoc()): ?>
          <article class="job-item">
            <div class="job-top">
              <p class="job-title"><?= htmlspecialchars($row['title']) ?></p>
              <span class="badge">Saved</span>
            </div>
            <p class="job-meta">
              <?= htmlspecialchars($row['company']) ?> â€¢ <?= htmlspecialchars($row['location']) ?>
              <?php if (!empty($row['type'])): ?> â€¢ <?= htmlspecialchars($row['type']) ?><?php endif; ?>
            </p>
            <p class="job-meta">Saved on: <?= htmlspecialchars(date('d M Y, H:i', strtotime($row['saved_at']))) ?></p>
            <div class="job-actions">
              <a href="job_details.php?id=<?= (int) $row['job_id'] ?>">View job details</a>
              <form class="inline-form" method="POST" action="remove_saved_job.php?id=<?= (int) $row['job_id'] ?>" onsubmit="return confirm('Remove this saved job?');">
                <button type="submit" class="link-btn">Remove saved job</button>
              </form>
            </div>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="muted">You have not saved any jobs yet.</p>
      <?php endif; ?>
    </section>
  </div>
</div>

<?php
$appliedStmt->close();
$savedStmt->close();
include '../includes/footer.php';
?>

