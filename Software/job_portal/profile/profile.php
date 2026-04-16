<?php
// Job Portal file: profile\profile.php
include "../includes/db.php";
include "../includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$defaultPic = "/Job_Portal/assets/images/profile_icon.png";
$profilePic = $defaultPic;

$cvRaw = $user['cv_file'] ?? '';
$cvRelative = ltrim(str_replace('\\', '/', $cvRaw), '/');
$cvAbsolutePath = $cvRelative ? realpath(__DIR__ . '/../' . $cvRelative) : false;
$projectRoot = realpath(__DIR__ . '/..');
$cvExists = $cvAbsolutePath && $projectRoot && strpos($cvAbsolutePath, $projectRoot) === 0 && file_exists($cvAbsolutePath);
$cvUrl = $cvExists ? "/Job_Portal/" . $cvRelative : '';

if (!empty($user['profile_picture'])) {
    $storedPic = $user['profile_picture'];

    $newPicPath = "../uploads/profile_picture/$user_id/" . $storedPic;
    if (file_exists($newPicPath)) {
        $profilePic = "/Job_Portal/uploads/profile_picture/$user_id/" . $storedPic;
    } else {
        $legacyPicPath = "../assets/uploads/profile_pictures/" . $storedPic;
        if (file_exists($legacyPicPath)) {
            $profilePic = "/Job_Portal/assets/uploads/profile_pictures/" . $storedPic;
        }
    }
}
?>

<div class="profile-container">
    <div class="profile-header-card">
        <div class="profile-picture-area">
            <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
                <label for="profileUpload" class="profile-image-label">
                    <img src="<?php echo $profilePic; ?>" class="profile-avatar-large" alt="Profile Picture">
                    <div class="change-overlay">Change Photo</div>
                </label>
                <input type="file" name="profile_picture" id="profileUpload" accept="image/*" hidden
                    onchange="this.form.submit()">
            </form>
        </div>
        <div class="profile-main-info">
            <h1><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></h1>
            <p><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
            <p><?php echo htmlspecialchars($user['location'] ?? 'Location not set'); ?></p>
            <p><?php echo htmlspecialchars($user['phone'] ?? 'Phone not added'); ?></p>
            <p class="member">Member since <?php echo date("F Y", strtotime($user['created_at'])); ?></p>
            <a href="edit_profile.php" class="primary-btn">Edit Profile</a>
        </div>
    </div>

    <div class="profile-grid">
        <div class="profile-section">
            <h3>About Me</h3>
            <p><?php echo nl2br(htmlspecialchars($user['bio'] ?: "Tell employers about yourself.")); ?></p>
        </div>

        <div class="profile-section">
            <h3>Skills</h3>
            <p><?php echo nl2br(htmlspecialchars($user['skills'] ?: "Add your skills.")); ?></p>
        </div>

        <div class="profile-section">
            <h3>Education</h3>
            <p><?php echo nl2br(htmlspecialchars($user['education'] ?: "Add your education.")); ?></p>
        </div>

        <div class="profile-section">
            <h3>Experience</h3>
            <p><?php echo nl2br(htmlspecialchars($user['experience'] ?: "Add your work experience.")); ?></p>
        </div>

        <div class="profile-section">
            <h3>CV / Resume</h3>
            <?php if ($cvExists): ?>
                <div class="action-row">
                    <a href="<?php echo htmlspecialchars($cvUrl); ?>" target="_blank" class="primary-btn">Download CV</a>
                    <form action="delete_cv.php" method="POST" style="display:inline;">
                        <button type="submit" class="danger-btn" onclick="return confirm('Delete CV?')">Remove CV</button>
                    </form>
                </div>
            <?php else: ?>
                <form action="upload_cv.php" method="POST" enctype="multipart/form-data">
                    <label for="cvUpload" class="primary-btn" style="display:inline-block;cursor:pointer;">Upload CV</label>
                    <input type="file" name="cv_file" id="cvUpload" accept=".pdf,.doc,.docx" hidden
                        onchange="this.form.submit()">
                </form>
            <?php endif; ?>
        </div>

        <div class="profile-section">
            <h3>Links</h3>
            <?php if (!empty($user['links'])): ?>
                <a href="<?php echo htmlspecialchars($user['links']); ?>" target="_blank" class="text-link"><?php echo htmlspecialchars($user['links']); ?></a>
            <?php else: ?>
                <p>No links added.</p>
            <?php endif; ?>
        </div>

        <div class="profile-section danger-zone">
            <h3>Account</h3>
            <p>Manage account deletion from the dedicated delete page.</p>
            <a href="delete_account.php" class="danger-btn-link"
                onclick="return confirm('This will take you to the permanent delete page. Are you sure?');">
                Delete Account
            </a>
        </div>
    </div>
</div>

<style>
    .profile-container {
        max-width: 1080px;
        margin: 60px auto;
        padding: 0 20px 40px;
    }

    .profile-header-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        padding: 36px;
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 32px;
        align-items: center;
        margin-bottom: 30px;
    }

    .profile-image-label {
        display: block;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        border: 4px solid #eef2ff;
    }

    .profile-avatar-large {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .change-overlay {
        position: absolute;
        inset: auto 0 0 0;
        background: rgba(17, 24, 39, 0.72);
        color: #fff;
        text-align: center;
        padding: 10px 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .profile-main-info h1 {
        margin: 0 0 10px 0;
        font-size: 2rem;
        color: #111827;
    }

    .profile-main-info p {
        margin: 6px 0;
        color: #4b5563;
    }

    .member {
        margin-top: 10px !important;
        font-size: 0.95rem;
        color: #6b7280 !important;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .profile-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.04);
    }

    .profile-section h3 {
        margin: 0 0 14px 0;
        font-size: 1.2rem;
        color: #111827;
    }

    .profile-section p {
        margin: 0;
        color: #4b5563;
        line-height: 1.7;
    }

    .primary-btn {
        display: inline-block;
        text-decoration: none;
        border: none;
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
        color: #fff;
        padding: 12px 22px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(108, 43, 217, 0.28);
        transition: all 0.25s ease;
    }

    .primary-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(108, 43, 217, 0.35);
    }

    .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .danger-btn,
    .danger-btn-link {
        display: inline-block;
        background: #dc2626;
        color: #fff;
        border: none;
        text-decoration: none;
        padding: 12px 18px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.25s ease;
    }

    .danger-btn:hover,
    .danger-btn-link:hover {
        background: #b91c1c;
    }

    .text-link {
        color: var(--color-primary);
        text-decoration: underline;
        word-break: break-all;
    }

    .danger-zone {
        border: 1px solid #fecaca;
        background: #fff7f7;
    }

    @media (max-width: 900px) {
        .profile-header-card {
            grid-template-columns: 1fr;
            text-align: center;
            justify-items: center;
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .profile-container {
            margin: 30px auto;
            padding: 0 14px 30px;
        }

        .profile-header-card,
        .profile-section {
            padding: 20px;
        }

        .profile-image-label {
            width: 160px;
            height: 160px;
        }
    }
</style>

