<?php
// job/job_details.php

include '../includes/db.php';
include '../includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$job = $res->fetch_assoc();

if (!$job) {
  echo '<div class="container my-5"><div class="card p-4">Job not found.</div></div>';
  include '../includes/footer.php';
  exit;
}

$applyLink = "apply_job.php?id=" . (int) $job['id'];
$saveLink = "save_job.php?id=" . (int) $job['id'];
?>

<style>
  :root {
    --main-purple: #6d28d9;
    --main-purple-dark: #5b21b6;
    --page-bg: #f3f4f8;
    --panel-bg: #ffffff;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --line: rgba(31, 41, 55, 0.08);
    --shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
  }

  .job-page {
    background: var(--page-bg);
    min-height: 100vh;
    padding: 30px 0 60px;
    font-family: "Inter", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  }

  .job-hero {
    background: linear-gradient(135deg, var(--main-purple), var(--main-purple-dark));
    color: #fff;
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
  }

  .job-title {
    margin: 0;
    font-size: 2.1rem;
    font-weight: 800;
  }

  .job-meta {
    margin-top: 6px;
    opacity: .95;
    font-size: .95rem;
    color: #fff;
  }

  .pill-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
  }

  .pill {
    font-size: .8rem;
    padding: 6px 11px;
    border-radius: 999px;
    background: rgba(255, 255, 255, .16);
    border: 1px solid rgba(255, 255, 255, .22);
    color: #fff;
    font-weight: 700;
  }

  .content-grid {
    display: grid;
    grid-template-columns: 1.7fr 1fr;
    gap: 22px;
    align-items: start;
  }

  @media(max-width:900px) {
    .content-grid {
      grid-template-columns: 1fr;
    }
  }

  .box {
    background: var(--panel-bg);
    border: 1px solid var(--line);
    border-radius: 18px;
    padding: 24px;
    box-shadow: var(--shadow);
    height: auto;
    max-height: none;
    overflow: visible;
    display: block;
    white-space: normal;
  }

  .section-title {
    margin-bottom: 16px;
    font-weight: 800;
    color: var(--text-main);
  }

  .desc {
    font-size: 0.96rem;
    line-height: 1.75;
    color: var(--text-main);
  }

  .desc p {
    margin-bottom: 14px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
  }

  .summary-row span {
    color: var(--text-muted);
  }

  .summary-row strong {
    color: var(--text-main);
  }

  .summary-divider {
    border: none;
    border-top: 1px solid var(--line);
    margin: 18px 0;
  }

  .btn-main,
  .btn-save {
    display: block;
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    text-align: center;
    transition: .2s ease;
  }

  .btn-main {
    background: var(--main-purple);
    color: #fff;
  }

  .btn-main:hover {
    background: var(--main-purple-dark);
    color: #fff;
    text-decoration: none;
  }

  .btn-save {
    margin-top: 10px;
    border: 1px solid var(--line);
    background: #fff;
    color: var(--text-main);
  }

  .btn-save:hover {
    background: #f9fafb;
    text-decoration: none;
  }
</style>

<div class="job-page">
  <div class="container my-5">

    <!-- HERO -->
    <div class="job-hero">

      <div style="max-width:750px;">

        <h2 class="job-title"><?= htmlspecialchars($job['title']) ?></h2>

        <p class="job-meta">
          <?= htmlspecialchars($job['company']) ?> • <?= htmlspecialchars($job['location']) ?>
        </p>

        <div class="pill-row">

          <?php if (!empty($job['category'])): ?>
            <span class="pill"><?= htmlspecialchars($job['category']) ?></span>
          <?php endif; ?>

          <?php if (!empty($job['type'])): ?>
            <span class="pill"><?= htmlspecialchars($job['type']) ?></span>
          <?php endif; ?>

          <?php if (!empty($job['salary'])): ?>
            <span class="pill">£<?= number_format($job['salary']) ?></span>
          <?php endif; ?>

          <?php if ((int) $job['is_promoted'] === 1): ?>
            <span class="pill">Promoted</span>
          <?php endif; ?>

        </div>
      </div>

    </div>

    <!-- CONTENT -->
    <div class="content-grid">

      <!-- DESCRIPTION -->
      <div class="box desc">

        <h4 class="section-title">Job Description</h4>

        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

      </div>

      <!-- SUMMARY -->
      <div class="box">

        <h5 class="section-title">Job Summary</h5>

        <div class="summary-row">
          <span>Company</span>
          <strong><?= htmlspecialchars($job['company']) ?></strong>
        </div>

        <div class="summary-row">
          <span>Location</span>
          <strong><?= htmlspecialchars($job['location']) ?></strong>
        </div>

        <?php if (!empty($job['category'])): ?>
          <div class="summary-row">
            <span>Category</span>
            <strong><?= htmlspecialchars($job['category']) ?></strong>
          </div>
        <?php endif; ?>

        <?php if (!empty($job['type'])): ?>
          <div class="summary-row">
            <span>Type</span>
            <strong><?= htmlspecialchars($job['type']) ?></strong>
          </div>
        <?php endif; ?>

        <?php if (!empty($job['salary'])): ?>
          <div class="summary-row">
            <span>Salary</span>
            <strong><?= htmlspecialchars($job['salary']) ?></strong>
          </div>
        <?php endif; ?>

        <hr class="summary-divider">

        <a class="btn-main" href="<?= htmlspecialchars($applyLink) ?>">Apply Now</a>
        <a class="btn-save" href="<?= htmlspecialchars($saveLink) ?>">Save Job</a>

        <?php if (!empty($job['category'])): ?>
          <a class="btn-save" style="margin-top:10px;" href="browse.php?category=<?= urlencode($job['category']) ?>">
            More jobs in <?= htmlspecialchars($job['category']) ?>
          </a>
        <?php endif; ?>

      </div>

    </div>
  </div>
</div>

<?php
$stmt->close();
include '../includes/footer.php';
?>