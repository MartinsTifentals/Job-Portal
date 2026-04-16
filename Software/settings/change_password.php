<?php
// Job Portal file: settings\change_password.php
include "../includes/header.php";
include "../includes/db.php";
include "../includes/auth_check.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current = $_POST["current_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT password FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($current, $user["password"])) {
        if ($new === $confirm) {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $update->bind_param("si", $newHash, $user_id);
            $update->execute();
            $message = "Password updated successfully.";
        } else {

            $message = "New passwords do not match.";
        }
    } else {
        $message = "Current password incorrect.";
    }
}

?>

<link rel="stylesheet" href="/Job_Portal/assets/css/settings.css">

<div class="settings-container">
    <h2>Change Password</h2>
    <?php if ($message)
        echo "<p>$message</p>"; ?>
    <form class="settings-form" method="POST">
        <label>Current Password</label>
        <input type="password" name="current_password" required>
        <label>New Password</label>
        <input type="password" name="new_password" required>
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>
        <button class="settings-btn">Update Password</button>
    </form>
</div>
<?php include "../includes/footer.php"; ?>

