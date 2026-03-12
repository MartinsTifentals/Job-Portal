<?php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';

$id = $_GET['id'];

$job = $conn->query("SELECT * FROM jobs WHERE id=$id")->fetch_assoc();

if (isset($_POST['update'])) {

    $title = $_POST['title'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $is_promoted = isset($_POST['is_promoted']) ? 1 : 0;

    $conn->query("UPDATE jobs 
SET title='$title',
location='$location',
category='$category',
description='$description',
is_promoted='$is_promoted'
WHERE id=$id");

    header("Location: manage_jobs.php");
    exit();
}
include '../includes/header.php';
?>

<link rel="stylesheet" href="\Job_Portal\assets\css\admin.css">

<div class="admin-container">
    <h1>Edit Job</h1>
    <form method="POST" class="admin-form">
        <input type="text" name="title" value="<?php echo $job['title']; ?>" required>
        <input type="text" name="location" value="<?php echo $job['location']; ?>" required>
        <input type="text" name="category" value="<?php echo $job['category']; ?>" required>
        <textarea name="description"><?php echo $job['description']; ?></textarea>
        <label class="promote-checkbox">
            <input type="checkbox" name="is_promoted" <?php if ($job['is_promoted'])
                echo "checked"; ?>>
            Promote this job
        </label>
        <button name="update">Update Job</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>