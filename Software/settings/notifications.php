<?php include "../includes/header.php"; ?>
<link rel="stylesheet" href="/Job_Portal/assets/css/settings.css">

<!-- Notification settings page container -->
<div class="settings-container">
    <h2>Notification Preferences</h2>
    <p class="settings-desc">Choose how JobMatrix keeps you informed. Toggle email alerts, application progress updates, and recommended promoted jobs to match your browsing mood.</p>

    <div class="settings-form">
        <!-- Email notifications toggle -->
        <div class="setting-option">
            <div>
                <label for="emailNotifications">Email Notifications</label>
                <p class="setting-description">Receive email alerts about account activity, messages, and important updates.</p>
            </div>
            <input type="checkbox" id="emailNotifications">
        </div>

        <!-- Application updates toggle -->
        <div class="setting-option">
            <div>
                <label for="applicationUpdates">Application Updates</label>
                <p class="setting-description">Keep application status changes visible in your dashboard and alert stream.</p>
            </div>
            <input type="checkbox" id="applicationUpdates">
        </div>

        <!-- Recommended jobs toggle -->
        <div class="setting-option">
            <div>
                <label for="recommendedJobs">Recommended Jobs</label>
                <p class="setting-description">Show or hide promoted job recommendations while browsing the site.</p>
            </div>
            <input type="checkbox" id="recommendedJobs">
        </div>

        <!-- Save button for notification settings -->
        <button type="button" class="settings-btn" id="saveNotificationSettings">Save Notification Settings</button>
        <div id="notificationSaveMessage" style="margin-top: 18px; display:none; color:#4b287d; font-weight:600;"></div>
    </div>
</div>

<style>
    .settings-desc {
        color: #5f4b8b;
        margin-bottom: 24px;
        line-height: 1.65;
    }

    .setting-description {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 0.95rem;
        max-width: 510px;
    }
</style>

<script>
    // Notification settings script
    document.addEventListener('DOMContentLoaded', function () {
        const defaults = {
            emailNotifications: "on",
            applicationUpdates: "on",
            recommendedJobs: "on"
        };

        // Form elements for toggling preferences
        const emailCheckbox = document.getElementById("emailNotifications");
        const updatesCheckbox = document.getElementById("applicationUpdates");
        const recommendedCheckbox = document.getElementById("recommendedJobs");
        const messageBox = document.getElementById("notificationSaveMessage");
        const saveButton = document.getElementById("saveNotificationSettings");

        const currentSettings = {
            emailNotifications: localStorage.getItem("emailNotifications") || defaults.emailNotifications,
            applicationUpdates: localStorage.getItem("applicationUpdates") || defaults.applicationUpdates,
            recommendedJobs: localStorage.getItem("recommendedJobs") || defaults.recommendedJobs
        };

        // Set toggles to saved preference values
        emailCheckbox.checked = currentSettings.emailNotifications === "on";
        updatesCheckbox.checked = currentSettings.applicationUpdates === "on";
        recommendedCheckbox.checked = currentSettings.recommendedJobs === "on";

        function savePreferences() {
            // Store user preferences in localStorage
            localStorage.setItem("emailNotifications", emailCheckbox.checked ? "on" : "off");
            localStorage.setItem("applicationUpdates", updatesCheckbox.checked ? "on" : "off");
            localStorage.setItem("recommendedJobs", recommendedCheckbox.checked ? "on" : "off");

            messageBox.textContent = "Notification settings updated. Your alerts and job recommendations will now follow your preferences.";
            messageBox.style.display = "block";

            // Refresh any live notification logic if available
            if (typeof window.applyNotificationPreferences === "function") {
                window.applyNotificationPreferences();
            }

            setTimeout(() => {
                messageBox.style.display = "none";
            }, 4800);
        }

        // Save button click handler
        saveButton.addEventListener("click", savePreferences);
    });
</script>

<?php include "../includes/footer.php"; ?>