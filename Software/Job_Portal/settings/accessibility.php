<?php include "../includes/header.php"; ?>

<div class="settings-container">
    <h2>Accessibility Settings</h2>
    <div class="setting-group">
        <label>Font Size</label>
        <select id="fontScale">
            <option value="1">Default</option>
            <option value="1.15">Large</option>
            <option value="1.3">Extra Large</option>
        </select>
    </div>
    <div class="setting-group">
        <label>Theme</label>
        <select id="theme">
            <option value="light">Light</option>
            <option value="dark">Dark</option>
        </select>
    </div>
    <div class="setting-group">
        <label>Dyslexia Friendly Font</label>
        <select id="dyslexia">
            <option value="off">Off</option>
            <option value="on">On</option>
        </select>
    </div>
    <div class="setting-group">
        <label>High Contrast</label>
        <select id="contrast">
            <option value="normal">Normal</option>
            <option value="high">High Contrast</option>
        </select>
    </div>
    <button onclick="saveAccessibility()" class="settings-btn">
        Save Settings
    </button>
</div>

<script>
    function saveAccessibility() {
        const fontScale = document.getElementById("fontScale").value;
        const theme = document.getElementById("theme").value;
        const dyslexia = document.getElementById("dyslexia").value;
        const contrast = document.getElementById("contrast").value;
        localStorage.setItem("fontScale", fontScale);
        localStorage.setItem("theme", theme);
        localStorage.setItem("dyslexia", dyslexia);
        localStorage.setItem("contrast", contrast);
        alert("Settings saved!");
    }
</script>
<?php include "../includes/footer.php"; ?>