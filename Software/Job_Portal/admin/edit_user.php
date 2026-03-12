<?php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

$id = $_GET['id'];

$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];

    $conn->query("UPDATE users 
    SET name='$name', email='$email'
    WHERE id=$id");

    header("Location: manage_users.php");
    exit();
}
?>

<link rel="stylesheet" href="\Job_Portal\assets\css\admin.css">

<div class="admin-container">
    <h1>Edit User</h1>
    <form method="POST" class="admin-form">
        <input type="text" name="name" value="<?php echo $user['name']; ?>">
        <input type="email" name="email" value="<?php echo $user['email']; ?>">
        <button name="update">Update User</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>