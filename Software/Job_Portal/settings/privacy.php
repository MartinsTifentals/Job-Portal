<?php include "../includes/header.php"; ?>
<link rel="stylesheet" href="/Job_Portal/assets/css/settings.css">

<div class="settings-container">
    <h2>Privacy Settings</h2>
    <div class="setting-option">
        <label>Profile Visibility</label>
        <select>
            <option>Public</option>
            <option>Only Employers</option>
            <option>Private</option>
        </select>
    </div>
    <div class="setting-option">
        <label>Show Email On Profile</label>
        <input type="checkbox">
    </div>
    <div class="setting-option">
        <label>Allow Employers To Contact Me</label>
        <input type="checkbox" checked>
    </div>
    <button class="settings-btn">Save Privacy Settings</button>
</div>
<?php include "../includes/footer.php"; ?>