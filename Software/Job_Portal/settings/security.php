<?php include "../includes/header.php"; ?>
<link rel="stylesheet" href="/Job_Portal/assets/css/settings.css">

<div class="settings-container">
    <h2>Security Settings</h2>
    <div class="setting-option">
        <label>Two-Factor Authentication</label>
        <input type="checkbox">
    </div>
    <div class="setting-option">
        <label>Login Alerts</label>
        <input type="checkbox" checked>
    </div>
    <div class="setting-option">
        <label>Remember Devices</label>
        <input type="checkbox" checked>
    </div>
    <button class="settings-btn">Save Security Settings</button>
</div>
<?php include "../includes/footer.php"; ?>