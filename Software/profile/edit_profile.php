<?php
// Job Portal file: profile\edit_profile.php
session_start();
include "../includes/db.php";
include "../includes/auth_check.php";

$user_id = (int) $_SESSION['user_id'];

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $education = trim($_POST['education'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $links = trim($_POST['links'] ?? '');

    // Validation
    if (strlen($name) < 2) {
        $error = "Name must be at least 2 characters";
    } elseif (!filter_var($phone, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^\+?[1-9]\d{1,14}$/']])) {
        $error = "Invalid phone number";
    } else {
        // Secure update with prepared statement
        $stmt = $conn->prepare("UPDATE users SET 
            name=?, location=?, phone=?, bio=?, skills=?, education=?, experience=?, links=?
            WHERE id=?");
        $stmt->bind_param("ssssssssi", $name, $location, $phone, $bio, $skills, $education, $experience, $links, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: edit_profile.php");
            exit();
        } else {
            $error = "Update failed: " . $conn->error;
        }
    }
}

// Get Current User Data
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<?php include "../includes/header.php"; ?>

<div class="profile-edit-container">
    <div class="edit-header">
        <h1>Edit Profile</h1>
        <a href="profile.php" class="back-btn">View Profile</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="edit-form" novalidate>
        <!-- Basic Info -->
        <div class="form-section">
            <h3>Basic Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                        value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required minlength="2"
                        maxlength="50" placeholder="Enter your full name">
                    <small>Minimum 2 characters</small>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location"
                        value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>" placeholder="City, Country">
                </div>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                    placeholder="+1234567890">
                <small>International format preferred</small>
            </div>
        </div>

        <!-- Bio & Summary -->
        <div class="form-section">
            <h3>Professional Summary</h3>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="4" maxlength="500"
                    placeholder="Tell employers about your experience, skills, and what makes you unique..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                <small>Max 500 characters</small>
            </div>
        </div>

        <!-- Skills & Experience -->
        <div class="form-section">
            <h3>Skills & Experience</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="skills">Skills (comma separated)</label>
                    <textarea id="skills" name="skills" rows="3" maxlength="500"
                        placeholder="PHP, JavaScript, MySQL, React, Node.js"><?php echo htmlspecialchars($user['skills'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="experience">Work Experience</label>
                    <textarea id="experience" name="experience" rows="3" maxlength="1000"
                        placeholder="Frontend Developer at ABC Corp (2022-Present)..."><?php echo htmlspecialchars($user['experience'] ?? ''); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="education">Education</label>
                <textarea id="education" name="education" rows="3" maxlength="1000"
                    placeholder="B.Sc Computer Science, University of Bradford (2020)"><?php echo htmlspecialchars($user['education'] ?? ''); ?></textarea>
            </div>
        </div>

        <!-- Links -->
        <div class="form-section">
            <h3>Links</h3>
            <div class="form-group">
                <label for="links">Portfolio / LinkedIn / GitHub</label>
                <input type="url" id="links" name="links" value="<?php echo htmlspecialchars($user['links'] ?? ''); ?>"
                    placeholder="https://linkedin.com/in/yourprofile">
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <button type="submit" class="save-btn">
                Save Changes
            </button>
            <a href="profile.php" class="cancel-btn">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
    .profile-edit-container {
        width: 40vw;
        min-width: 900px;
        max-width: 1400px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 2px solid #f3f4f6;
    }

    .edit-header h1 {
        margin: 0;
        font-size: 2.2rem;
        color: var(--color-dark);
        font-weight: 700;
    }


    .back-btn {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 10px 16px;
        border-radius: 8px;
        border: 2px solid var(--color-primary);
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: var(--color-primary);
        color: white;
    }

    .success-message {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        padding: 16px 20px;
        border-radius: 10px;
        border-left: 4px solid #10b981;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .error-message {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        padding: 16px 20px;
        border-radius: 10px;
        border-left: 4px solid #ef4444;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .form-section {
        background: #f8fafc;
        padding: 30px;
        border-radius: var(--border-radius);
        margin-bottom: 30px;
        border: 1px solid #e2e8f0;
    }

    .form-section h3 {
        margin: 0 0 25px 0;
        color: var(--color-dark);
        font-size: 1.4rem;
        font-weight: 600;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .form-group {
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: #374151;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 16px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        background: #fff;
        font-family: inherit;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 4px rgba(108, 43, 217, 0.1);
    }

    .required {
        color: #ef4444;
    }

    .form-group small {
        color: #9ca3af;
        font-size: 0.85rem;
        margin-top: 6px;
        display: block;
    }

    .char-counter {
        position: absolute;
        bottom: 14px;
        right: 18px;
        color: #9ca3af;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Segoe UI', monospace;
    }

    .form-actions {
        display: flex;
        gap: 20px;
        justify-content: flex-end;
        padding-top: 30px;
        border-top: 2px solid #f3f4f6;
        margin-top: 30px;
    }

    .save-btn {
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
        color: white;
        border: none;
        padding: 16px 35px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(108, 43, 217, 0.3);
        transition: all 0.3s ease;
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 43, 217, 0.4);
    }

    .cancel-btn {
        color: #6b7280;
        text-decoration: none;
        padding: 16px 35px;
        border-radius: 12px;
        font-weight: 600;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .cancel-btn:hover {
        background: #f3f4f6;
        color: #374151;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .profile-edit-container {
            margin: 30px 20px;
            padding: 25px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .save-btn,
        .cancel-btn {
            width: 100%;
        }

        .edit-header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }
    }
</style>

<script>
    // Real-time character counters
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.cssText = 'position:absolute;bottom:12px;right:16px;color:#888;font-size:12px;font-family:monospace';
        textarea.parentNode.style.position = 'relative';
        textarea.parentNode.appendChild(counter);

        function updateCounter() {
            const remaining = textarea.maxLength - textarea.value.length;
            counter.textContent = remaining;
            counter.style.color = remaining < 50 ? '#e74c3c' : '#888';
        }
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });

    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 10) value = value.slice(0, 15);
        e.target.value = value;
    });
</script>

<?php include "../includes/footer.php"; ?>
