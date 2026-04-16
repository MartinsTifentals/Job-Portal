<?php
// Job Portal file: employers\post_job.php
include "../includes/employer_check.php";
include "../includes/header.php";
include "../includes/db.php";

// User-facing status messages for the job post submission form.
$success = '';
$error = '';

// Default form values are stored here so the form can be repopulated
// after a failed validation attempt.
$formData = [
    'title' => '',
    'company' => '',
    'location' => '',
    'category' => '',
    'type' => '',
    'experience_level' => '',
    'work_arrangement' => '',
    'deadline' => '',
    'apply_link' => '',
    'salary' => '',
    'description' => '',
];

// Predefined select menu options used for form validation and rendering.
$categories = ['Programming', 'Cybersecurity', 'Data', 'Design', 'Networking', 'Management'];
$types = ['Full Time', 'Part Time', 'Contract', 'Internship', 'Remote'];
$experienceLevels = ['Entry', 'Mid', 'Senior'];
$workArrangements = ['On-site', 'Hybrid', 'Remote'];

// Detect whether the jobs table already has the employer_id column.
// This supports older schema versions without breaking the page.
$ownerColumnExists = false;
$ownerColumnCheck = mysqli_query($conn, "SHOW COLUMNS FROM jobs LIKE 'employer_id'");
if ($ownerColumnCheck && mysqli_num_rows($ownerColumnCheck) > 0) {
    $ownerColumnExists = true;
}

if (!$ownerColumnExists) {
    @mysqli_query($conn, "ALTER TABLE jobs ADD COLUMN employer_id INT NULL AFTER id");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Normalize and preserve submitted values for later validation and display.
    foreach ($formData as $key => $value) {
        $formData[$key] = isset($_POST[$key]) ? trim($_POST[$key]) : '';
    }

    $title = $formData['title'];
    $company = $formData['company'];
    $location = $formData['location'];
    $category = $formData['category'];
    $type = $formData['type'];
    $experience_level = $formData['experience_level'];
    $work_arrangement = $formData['work_arrangement'];
    $deadline = $formData['deadline'];
    $apply_link = $formData['apply_link'];
    $salary = $formData['salary'];
    $description = $formData['description'];

    // Validate required fields and allowed option values.
    if ($title === '' || $company === '' || $location === '' || $category === '' || $type === '' || $description === '') {
        $error = "Please complete all required fields.";
    } elseif (!in_array($category, $categories, true)) {
        $error = "Please select a valid category.";
    } elseif (!in_array($type, $types, true)) {
        $error = "Please select a valid job type.";
    } else {
        $salaryValue = is_numeric($salary) ? (int)$salary : 0;
        $employerId = (int)$_SESSION['user_id'];

// Use prepared statements to protect against SQL injection.
        if ($ownerColumnExists) {
            $stmt = $conn->prepare(" 
                INSERT INTO jobs (
                    employer_id,
                    title,
                    company,
                    location,
                    salary,
                    type,
                    category,
                    description,
                    created_at,
                    is_promoted,
                    approval_status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0, 'pending')
            ");

            if ($stmt) {
                $stmt->bind_param(
                    "ississss",
                    $employerId,
                    $title,
                    $company,
                    $location,
                    $salaryValue,
                    $type,
                    $category,
                    $description
                );
            }
        } else {
            // Fallback insert for schemas without employer_id column.
            $stmt = $conn->prepare("
                INSERT INTO jobs (
                    title,
                    company,
                    location,
                    salary,
                    type,
                    category,
                    description,
                    created_at,
                    is_promoted,
                    approval_status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 0, 'pending')
            ");

            if ($stmt) {
                $stmt->bind_param(
                    "ssissss",
                    $title,
                    $company,
                    $location,
                    $salaryValue,
                    $type,
                    $category,
                    $description
                );
            }
        }

        if ($stmt) {

            if ($stmt->execute()) {
                $success = "Job posted successfully. It is now pending admin approval before appearing publicly.";
                foreach ($formData as $key => $value) {
                    $formData[$key] = '';
                }
            } else {
                $error = "Unable to save job right now. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Could not prepare the job submission request.";
        }
    }
}
?>

<style>
    .post-job-page {
        max-width: 1100px;
        margin: 40px auto 70px;
        padding: 0 16px;
    }

    .post-hero {
        background: linear-gradient(135deg, #5f27cd, #7e57ff);
        color: #fff;
        border-radius: 16px;
        padding: 34px 28px;
        margin-bottom: 24px;
        box-shadow: 0 12px 30px rgba(95, 39, 205, 0.25);
    }

    .post-hero h1 {
        margin: 0 0 8px;
        font-size: 2rem;
    }

    .post-hero p {
        margin: 0;
        opacity: 0.95;
        max-width: 760px;
    }

    .post-layout {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
    }

    .post-card,
    .tips-card {
        background: #fff;
        border: 1px solid #ececf2;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 6px 20px rgba(20, 20, 43, 0.04);
    }

    .post-card h2,
    .tips-card h2 {
        margin-top: 0;
        margin-bottom: 18px;
        font-size: 1.2rem;
        color: #222;
    }

    .alert {
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 16px;
        font-size: 0.95rem;
    }

    .alert-success {
        background: #d9f8e5;
        border: 1px solid #b9eccc;
        color: #106b33;
    }

    .alert-error {
        background: #ffe1e5;
        border: 1px solid #ffc2ca;
        color: #8a1d2b;
    }

    .field-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 14px;
    }

    .field label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #3a3a4d;
    }

    .field input,
    .field select,
    .field textarea {
        border: 1px solid #d8d8e2;
        border-radius: 10px;
        padding: 11px 12px;
        font-size: 0.95rem;
        width: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        border-color: #7a54f5;
        box-shadow: 0 0 0 4px rgba(122, 84, 245, 0.12);
        outline: none;
    }

    .field textarea {
        min-height: 160px;
        resize: vertical;
    }

    .field-full {
        grid-column: 1 / -1;
    }

    .hint {
        color: #777;
        font-size: 0.82rem;
        margin-top: 2px;
    }

    .submit-row {
        margin-top: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .submit-row small {
        color: #666;
    }

    .btn-submit {
        border: none;
        background: #5f27cd;
        color: #fff;
        font-weight: 700;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .btn-submit:hover {
        background: #4e1eb0;
        transform: translateY(-1px);
    }

    .tips-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 12px;
    }

    .tips-card li {
        background: #f6f5ff;
        border: 1px solid #ece8ff;
        border-radius: 10px;
        padding: 12px;
        color: #444;
        font-size: 0.93rem;
    }

    .tips-card li strong {
        color: #35207d;
    }

    @media (max-width: 900px) {
        .post-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .field-grid {
            grid-template-columns: 1fr;
        }

        .post-hero h1 {
            font-size: 1.6rem;
        }
    }
</style>

<div class="post-job-page">
    <section class="post-hero">
        <h1>Create a Job Listing</h1>
        <p>Use this form to publish a complete, high-quality job post. Better details usually mean better applicants and faster hiring.</p>
    </section>

    <div class="post-layout">
        <section class="post-card">
            <h2>Job Information</h2>

            <?php if ($success !== ''): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="post_job.php">
                <div class="field-grid">
                    <div class="field">
                        <label for="title">Job Title *</label>
                        <input id="title" name="title" type="text" placeholder="e.g. Frontend Developer" required value="<?php echo htmlspecialchars($formData['title']); ?>">
                    </div>

                    <div class="field">
                        <label for="company">Company *</label>
                        <input id="company" name="company" type="text" placeholder="e.g. Bright Labs Ltd" required value="<?php echo htmlspecialchars($formData['company']); ?>">
                    </div>

                    <div class="field">
                        <label for="location">Location *</label>
                        <input id="location" name="location" type="text" placeholder="e.g. London, UK" required value="<?php echo htmlspecialchars($formData['location']); ?>">
                    </div>

                    <div class="field">
                        <label for="salary">Salary (optional)</label>
                        <input id="salary" name="salary" type="number" min="0" placeholder="e.g. 50000" value="<?php echo htmlspecialchars($formData['salary']); ?>">
                    </div>

                    <div class="field">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $formData['category'] === $cat ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="type">Job Type *</label>
                        <select id="type" name="type" required>
                            <option value="">Select job type</option>
                            <?php foreach ($types as $jobType): ?>
                                <option value="<?php echo htmlspecialchars($jobType); ?>" <?php echo $formData['type'] === $jobType ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($jobType); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="experience_level">Experience Level</label>
                        <select id="experience_level" name="experience_level">
                            <option value="">Select level</option>
                            <?php foreach ($experienceLevels as $level): ?>
                                <option value="<?php echo htmlspecialchars($level); ?>" <?php echo $formData['experience_level'] === $level ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($level); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="work_arrangement">Work Arrangement</label>
                        <select id="work_arrangement" name="work_arrangement">
                            <option value="">Select arrangement</option>
                            <?php foreach ($workArrangements as $arrangement): ?>
                                <option value="<?php echo htmlspecialchars($arrangement); ?>" <?php echo $formData['work_arrangement'] === $arrangement ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($arrangement); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="deadline">Application Deadline</label>
                        <input id="deadline" name="deadline" type="date" value="<?php echo htmlspecialchars($formData['deadline']); ?>">
                    </div>

                    <div class="field">
                        <label for="apply_link">Apply Link / Email</label>
                        <input id="apply_link" name="apply_link" type="text" placeholder="e.g. jobs@company.com" value="<?php echo htmlspecialchars($formData['apply_link']); ?>">
                    </div>

                    <div class="field field-full">
                        <label for="description">Job Description *</label>
                        <textarea id="description" name="description" placeholder="Describe responsibilities, requirements, and benefits..." required><?php echo htmlspecialchars($formData['description']); ?></textarea>
                        <div class="hint">Tip: Include responsibilities, requirements, and what makes this role attractive.</div>
                    </div>
                </div>

                <div class="submit-row">
                    <small>All submitted jobs are now marked as <strong>Pending</strong> and become visible only after admin approval.</small>
                    <button class="btn-submit" type="submit">Post Job</button>
                </div>
            </form>
        </section>

        <aside class="tips-card">
            <h2>Posting Tips</h2>
            <ul>
                <li><strong>Be specific:</strong> Clear role titles and requirements attract the right candidates.</li>
                <li><strong>Share salary:</strong> Listings with salary ranges usually get better response rates.</li>
                <li><strong>Keep it focused:</strong> Avoid long blocks of text; use concise details.</li>
                <li><strong>Explain growth:</strong> Mention progression opportunities and learning support.</li>
                <li><strong>Set expectations:</strong> Clarify remote/hybrid/on-site setup and work hours.</li>
            </ul>
        </aside>
    </div>
</div>

<?php include "../includes/footer.php"; ?>

