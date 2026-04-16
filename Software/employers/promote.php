<?php
// Job Portal file: employers\promote.php
include "../includes/employer_check.php";
include "../includes/db.php";

$success = '';
$error = '';
$selectedJobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$selectedPlanId = 0;

$currentUserId = (int)$_SESSION['user_id'];
$currentUserRole = 'user';

$roleQuery = mysqli_query($conn, "SELECT role FROM users WHERE id = $currentUserId LIMIT 1");
if ($roleQuery && mysqli_num_rows($roleQuery) > 0) {
    $roleRow = mysqli_fetch_assoc($roleQuery);
    $currentUserRole = $roleRow['role'] ?? 'user';
}

$plans = [
    [
        'id' => 1,
        'name' => 'Boost 7',
        'price' => 'Â£9.99',
        'duration_label' => '7 Days',
        'duration_days' => 7,
        'tag' => 'Starter',
        'benefits' => [
            'Highlighted in listings',
            'Boosted search visibility',
            'Great for urgent hires'
        ],
    ],
    [
        'id' => 2,
        'name' => 'Boost 14',
        'price' => 'Â£14.99',
        'duration_label' => '14 Days',
        'duration_days' => 14,
        'tag' => 'Popular',
        'benefits' => [
            'Everything in Boost 7',
            'Longer homepage visibility',
            'Better applicant volume'
        ],
    ],
    [
        'id' => 3,
        'name' => 'Boost 30',
        'price' => 'Â£24.99',
        'duration_label' => '30 Days',
        'duration_days' => 30,
        'tag' => 'Maximum Reach',
        'benefits' => [
            'Top exposure for 1 month',
            'Best for strategic hiring',
            'Premium highlighted style'
        ],
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedJobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
    $selectedPlanId = isset($_POST['plan_id']) ? (int)$_POST['plan_id'] : 0;

    $selectedPlan = null;
    foreach ($plans as $plan) {
        if ($plan['id'] === $selectedPlanId) {
            $selectedPlan = $plan;
            break;
        }
    }

    if ($selectedJobId <= 0 || !$selectedPlan) {
        $error = "Please choose a job and a valid promotion plan.";
    } else {
        header("Location: ../checkout.php?type=promotion&job_id=" . $selectedJobId . "&plan_id=" . $selectedPlanId);
        exit;
    }
}

$jobs = [];

$ownerColumnExists = false;
$ownerColumnCheck = mysqli_query($conn, "SHOW COLUMNS FROM jobs LIKE 'employer_id'");
if ($ownerColumnCheck && mysqli_num_rows($ownerColumnCheck) > 0) {
    $ownerColumnExists = true;
}

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
    .promote-page {
        max-width: 1180px;
        margin: 42px auto 80px;
        padding: 0 16px;
    }

    .promote-hero {
        background: linear-gradient(135deg, #6c2bd9, #8a5cff);
        border-radius: 16px;
        color: #fff;
        padding: 32px 28px;
        margin-bottom: 22px;
        box-shadow: 0 14px 30px rgba(69, 31, 145, 0.25);
    }

    .promote-hero h1 {
        margin: 0 0 8px;
        font-size: 2rem;
    }

    .promote-hero p {
        margin: 0;
        opacity: 0.95;
        max-width: 820px;
    }

    .promote-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 22px;
    }

    .panel {
        background: #fff;
        border: 1px solid #ececf3;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(20, 20, 40, 0.04);
    }

    .panel h2 {
        margin-top: 0;
        margin-bottom: 14px;
        color: #222;
        font-size: 1.2rem;
    }

    .status-banner {
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 14px;
        font-size: 0.95rem;
    }

    .status-success {
        background: #d9f8e5;
        border: 1px solid #bbeecf;
        color: #126f38;
    }

    .status-error {
        background: #ffe2e6;
        border: 1px solid #ffc3cb;
        color: #902132;
    }

    .selector {
        margin-bottom: 18px;
    }

    .selector label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #3b3b52;
    }

    .selector select {
        width: 100%;
        border: 1px solid #d9d9e4;
        border-radius: 10px;
        padding: 12px;
        font-size: 0.95rem;
    }

    .selector select:focus {
        outline: none;
        border-color: #7b52f6;
        box-shadow: 0 0 0 4px rgba(123, 82, 246, 0.13);
    }

    .plan-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    .plan-card {
        border: 1px solid #ece9fb;
        background: #faf9ff;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .plan-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .plan-name {
        font-size: 1rem;
        font-weight: 700;
        color: #2b2252;
    }

    .plan-tag {
        font-size: 0.72rem;
        background: #ece5ff;
        border: 1px solid #d7cbff;
        color: #5b32cb;
        border-radius: 999px;
        padding: 4px 8px;
        white-space: nowrap;
    }

    .plan-price {
        font-size: 1.45rem;
        font-weight: 800;
        color: #6a30d6;
        line-height: 1.1;
    }

    .plan-duration {
        color: #666;
        font-size: 0.9rem;
    }

    .plan-card ul {
        margin: 4px 0 2px;
        padding-left: 17px;
        color: #4f4f63;
        font-size: 0.9rem;
        min-height: 84px;
    }

    .plan-card li {
        margin-bottom: 7px;
    }

    .plan-card button {
        margin-top: auto;
        border: none;
        background: #6128cf;
        color: #fff;
        border-radius: 10px;
        padding: 11px 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .plan-card button:hover {
        background: #4f1fb0;
        transform: translateY(-1px);
    }


    .notes {
        color: #666;
        font-size: 0.88rem;
        margin-top: 14px;
        line-height: 1.45;
    }

    @media (max-width: 1050px) {
        .promote-grid {
            grid-template-columns: 1fr;
        }

        .plan-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include "../includes/header.php"; ?>

<div class="promote-page">
    <section class="promote-hero">
        <h1>Promote a Job Listing</h1>
        <p>Give your jobs more visibility and attract stronger candidates faster. Pick a job, choose a plan, and continue to checkout.</p>
    </section>

    <div class="promote-grid">
        <section class="panel">
            <h2>Promotion Setup</h2>

            <?php if ($success !== ''): ?>
                <div class="status-banner status-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="status-banner status-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="promote.php">
                <div class="selector">
                    <label for="job_id">Choose job to promote</label>
                    <select id="job_id" name="job_id" required>
                        <option value="">Select a job...</option>
                        <?php foreach ($jobs as $job): ?>
                            <option value="<?php echo (int)$job['id']; ?>" <?php echo $selectedJobId === (int)$job['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($job['title'] . ' â€” ' . $job['company'] . ' (' . $job['location'] . ')'); ?>
                                <?php echo ((int)$job['is_promoted'] === 1) ? ' [Promoted]' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="plan-grid">
                    <?php foreach ($plans as $plan): ?>
                        <div class="plan-card">
                            <div class="plan-top">
                                <div class="plan-name"><?php echo htmlspecialchars($plan['name']); ?></div>
                                <div class="plan-tag"><?php echo htmlspecialchars($plan['tag']); ?></div>
                            </div>
                            <div class="plan-price"><?php echo htmlspecialchars($plan['price']); ?></div>
                            <div class="plan-duration"><?php echo htmlspecialchars($plan['duration_label']); ?></div>
                            <ul>
                                <?php foreach ($plan['benefits'] as $benefit): ?>
                                    <li><?php echo htmlspecialchars($benefit); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="submit" name="plan_id" value="<?php echo (int)$plan['id']; ?>">
                                Activate <?php echo htmlspecialchars($plan['name']); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </form>

            <p class="notes">
            </p>
        </section>

    </div>
</div>

<?php include "../includes/footer.php"; ?>

