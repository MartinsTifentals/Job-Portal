<?php 
include "../includes/header.php";
include"../includes/auth_check.php";
?>
<link rel="stylesheet" href="/Job_Portal/assets/css/settings.css">

<div class="settings-page">
    <h1>Settings</h1>
    <p class="settings-desc">Manage your account preferences, privacy and security.</p>
    <div class="settings-grid">
        <a href="change_password.php" class="settings-card">
            <h3>Change Password</h3>
            <p>Update your account password.</p>
        </a>
        <a href="security.php" class="settings-card">
            <h3>Security</h3>
            <p>Manage login security and account protection.</p>
        </a>
        <a href="notifications.php" class="settings-card">
            <h3>Notifications</h3>
            <p>Choose how JobMatrix contacts you.</p>
        </a>
        <a href="accessibility.php" class="settings-card">
            <h3>Accessibility</h3>
            <p>Adjust visual settings and font sizes.</p>
        </a>
        <a href="privacy.php" class="settings-card">
            <h3>Privacy</h3>
            <p>Control who can see your profile.</p>
        </a>
    </div>
</div>
<?php include "../includes/footer.php"; ?>