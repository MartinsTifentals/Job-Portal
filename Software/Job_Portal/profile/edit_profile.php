<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = (int)$_SESSION['user_id']; // safer numeric id

// UPDATE PROFILE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $links = mysqli_real_escape_string($conn, $_POST['links']);

    $update = "UPDATE users SET
        name='$name',
        location='$location',
        phone='$phone',
        bio='$bio',
        skills='$skills',
        education='$education',
        experience='$experience',
        links='$links'
        WHERE id=$user_id";

    mysqli_query($conn, $update) or die(mysqli_error($conn));

    header("Location: profile.php");
    exit();
}

// GET USER DATA
$query = "SELECT * FROM users WHERE id=$user_id";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$user = mysqli_fetch_assoc($result);

?>

<?php include "../includes/header.php"; ?>

<div class="profile-container">
    <h1>Edit Profile</h1>
    <form method="POST" class="edit-profile-form">
        <label>Full Name</label>
        <input type="text" name="name" value="<?php echo $user['name'] ?? ''; ?>">
        <label>Location</label>
        <input type="text" name="location" value="<?php echo $user['location'] ?? ''; ?>">
        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
        <label>Bio</label>
        <textarea name="bio"><?php echo $user['bio'] ?? ''; ?></textarea>
        <label>Skills</label>
        <textarea name="skills"><?php echo $user['skills'] ?? ''; ?></textarea>
        <label>Education</label>
        <textarea name="education"><?php echo $user['education'] ?? ''; ?></textarea>
        <label>Experience</label>
        <textarea name="experience"><?php echo $user['experience'] ?? ''; ?></textarea>
        <label>Link URL</label>
        <input type="text" name="links" value="<?php echo $user['links'] ?? ''; ?>">
        <button type="submit" class="edit-btn">Save Changes</button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>