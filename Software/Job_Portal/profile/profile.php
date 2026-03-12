<?php
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

if (!empty($user['profile_picture']) && file_exists("../assets/uploads/profile_pictures/" . $user['profile_picture'])) {
    $profilePic = "/Job_Portal/assets/uploads/profile_pictures/" . $user['profile_picture'];
} else {
    $profilePic = $defaultPic;
}
?>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-picture-area">
            <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
                <label for="profileUpload" class="profile-image-label">
                    <img src="<?php echo $profilePic; ?>" class="profile-avatar-large">
                    <div class="change-overlay">Change Photo</div>
                </label>
                <input type="file" name="profile_picture" id="profileUpload" accept="image/*" hidden
                    onchange="this.form.submit()">
            </form>
        </div>
        <div class="profile-main-info">
            <h1><?php echo $user['name']; ?></h1>
            <p><?php echo $user['email']; ?></p>
            <p><?php echo $user['location'] ?? 'Location not set'; ?></p>
            <p><?php echo $user['phone'] ?? 'Phone not added'; ?></p>
            <p class="member">Member since <?php echo date("F Y", strtotime($user['created_at'])); ?></p>
            <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
        </div>
    </div>
    <div class="profile-grid">
        <div class="profile-section">
            <h3>About Me</h3>
            <p><?php echo $user['bio'] ?: "Tell employers about yourself."; ?></p>
        </div>
        <div class="profile-section">
            <h3>Skills</h3>
            <p><?php echo $user['skills'] ?: "Add your skills."; ?></p>
        </div>
        <div class="profile-section">
            <h3>Education</h3>
            <p><?php echo $user['education'] ?: "Add your education."; ?></p>
        </div>
        <div class="profile-section">
            <h3>Experience</h3>
            <p><?php echo $user['experience'] ?: "Add your work experience."; ?></p>
        </div>
        <div class="profile-section">
            <h3>CV / Resume</h3>
            <?php if ($user['cv']) { ?>
                <a href="/Job_Portal/uploads/cv/<?php echo $user['cv']; ?>" target="_blank">View CV</a>
            <?php } else { ?>
                <p>No CV uploaded</p>
            <?php } ?>
            <a href="upload_cv.php" class="action-btn">Upload CV</a>
        </div>
        <div class="profile-section">
            <h3>Links</h3>
            <?php if ($user['links']) { ?>
                <a href="<?php echo $user['links']; ?>" target="_blank">links</a>
            <?php } else { ?>
                <p>No Links added</p>
            <?php } ?>
        </div>
        <div class="profile-section danger-zone">
            <h3>Account Settings</h3>
            <a href="delete_account.php" class="danger-btn">Delete Account</a>
        </div>
    </div>
</div>