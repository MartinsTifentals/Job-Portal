<?php
// Job Portal file: job\apply_job.php
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$job_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($job_id <= 0) {
    header('Location: browse.php');
    exit;
}

$jobStmt = $conn->prepare("SELECT id, title, company, location, type FROM jobs WHERE id = ?");
$jobStmt->bind_param("i", $job_id);
$jobStmt->execute();
$job = $jobStmt->get_result()->fetch_assoc();
$jobStmt->close();

if (!$job) {
    header('Location: browse.php');
    exit;
}

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

$coverLetterColumn = $conn->query("SHOW COLUMNS FROM applications LIKE 'cover_letter'");
if ($coverLetterColumn && $coverLetterColumn->num_rows === 0) {
    $conn->query("ALTER TABLE applications ADD COLUMN cover_letter TEXT NULL AFTER status");
}

$dupStmt = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
$dupStmt->bind_param("ii", $user_id, $job_id);
$dupStmt->execute();
$alreadyApplied = $dupStmt->get_result()->fetch_assoc();
$dupStmt->close();

if ($alreadyApplied) {
    header("Location: job_details.php?id={$job_id}&info=already_applied");
    exit;
}

$userStmt = $conn->prepare("SELECT name, location, phone, bio, skills, education, experience, cv_file FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();
$userStmt->close();

$missingFields = [];
$requiredFields = [
    'name' => 'Full Name',
    'location' => 'Location',
    'phone' => 'Phone Number',
    'bio' => 'Bio',
    'skills' => 'Skills',
    'education' => 'Education',
    'experience' => 'Experience'
];

foreach ($requiredFields as $dbField => $label) {
    if (empty(trim((string)($user[$dbField] ?? '')))) {
        $missingFields[] = $label;
    }
}

$cvRaw = $user['cv_file'] ?? '';
$cvRelative = ltrim(str_replace('\\', '/', $cvRaw), '/');
$cvAbsolutePath = $cvRelative ? realpath(__DIR__ . '/../' . $cvRelative) : false;
$projectRoot = realpath(__DIR__ . '/..');
$cvExists = $cvAbsolutePath && $projectRoot && strpos($cvAbsolutePath, $projectRoot) === 0 && file_exists($cvAbsolutePath);

$errors = [];
$coverLetter = '';
$confirmTruth = '';
$confirmReady = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coverLetter = trim($_POST['cover_letter'] ?? '');
    $confirmTruth = $_POST['confirm_truth'] ?? '';
    $confirmReady = $_POST['confirm_ready'] ?? '';

    if (!empty($missingFields)) {
        $errors[] = 'Please complete all required profile information before applying.';
    }

    if (!$cvExists) {
        $errors[] = 'Please upload your CV before applying.';
    }

    if (strlen($coverLetter) < 150) {
        $errors[] = 'Cover letter must be at least 150 characters.';
    }

    if ($confirmTruth !== '1') {
        $errors[] = 'You must confirm that your application information is accurate.';
    }

    if ($confirmReady !== '1') {
        $errors[] = 'You must confirm that you are ready to be contacted by the employer.';
    }

    if (empty($errors)) {
        $insert = $conn->prepare("INSERT INTO applications (user_id, job_id, status, cover_letter) VALUES (?, ?, 'applied', ?)");
        $insert->bind_param("iis", $user_id, $job_id, $coverLetter);
        $insert->execute();
        $insert->close();

        header("Location: job_details.php?id={$job_id}&success=applied");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="/Job_Portal/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div style="max-width:900px;margin:30px auto;padding:0 16px 40px;">
    <div style="background:#fff;border:1px solid #ececf2;border-radius:14px;padding:20px;">
        <h2 style="margin-top:0;">Apply for <?= htmlspecialchars($job['title']) ?></h2>
        <p style="color:#667085;margin-top:4px;">
            <?= htmlspecialchars($job['company']) ?> â€¢ <?= htmlspecialchars($job['location']) ?>
            <?php if (!empty($job['type'])): ?> â€¢ <?= htmlspecialchars($job['type']) ?><?php endif; ?>
        </p>

        <?php if (!empty($missingFields) || !$cvExists): ?>
            <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;padding:12px 14px;margin:14px 0;">
                <strong style="display:block;margin-bottom:6px;color:#9a3412;">Profile requirements not complete</strong>
                <?php if (!empty($missingFields)): ?>
                    <p style="margin:0 0 6px;color:#7c2d12;">Missing profile fields: <?= htmlspecialchars(implode(', ', $missingFields)) ?></p>
                <?php endif; ?>
                <?php if (!$cvExists): ?>
                    <p style="margin:0;color:#7c2d12;">CV is missing. Upload one from your profile page.</p>
                <?php endif; ?>
                <div style="margin-top:10px;">
                    <a href="/Job_Portal/profile/edit_profile.php" style="margin-right:10px;">Complete Profile</a>
                    <a href="/Job_Portal/profile/profile.php">Go to Profile / Upload CV</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 14px;margin:14px 0;color:#991b1b;">
                <ul style="margin:0;padding-left:18px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="apply_job.php?id=<?= (int)$job_id ?>">
            <div style="margin-bottom:14px;">
                <label for="cover_letter" style="font-weight:600;display:block;margin-bottom:8px;">Cover Letter (required)</label>
                <textarea id="cover_letter" name="cover_letter" rows="10" required minlength="150"
                    style="width:100%;border:1px solid #d0d5dd;border-radius:10px;padding:12px;"
                    placeholder="Explain why you're a strong fit for this role, your relevant experience, and what value you can bring."><?= htmlspecialchars($coverLetter) ?></textarea>
                <small style="color:#667085;">Minimum 150 characters.</small>
            </div>

            <div style="margin-bottom:10px;">
                <label style="display:flex;gap:10px;align-items:flex-start;">
                    <input type="checkbox" name="confirm_truth" value="1" <?= $confirmTruth === '1' ? 'checked' : '' ?>>
                    <span>I confirm all details in my profile and application are accurate.</span>
                </label>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:flex;gap:10px;align-items:flex-start;">
                    <input type="checkbox" name="confirm_ready" value="1" <?= $confirmReady === '1' ? 'checked' : '' ?>>
                    <span>I confirm I am ready to be contacted by this employer and attend interviews.</span>
                </label>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="submit" style="background:#720963;color:#fff;border:none;border-radius:10px;padding:11px 16px;font-weight:700;cursor:pointer;">
                    Submit Application
                </button>
                <a href="job_details.php?id=<?= (int)$job_id ?>" style="display:inline-block;padding:11px 16px;border:1px solid #d0d5dd;border-radius:10px;text-decoration:none;color:#111827;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

