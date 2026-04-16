<?php
// Job Portal file: admin\edit_user.php
include '../includes/auth_check.php';
include '../includes/admin_check.php';
include '../includes/db.php';
include '../includes/header.php';

// Validate the user ID passed in the query string.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['error'] = 'Invalid user ID';
    header("Location: manage_users.php");
    exit();
}

// Load the user record for editing, excluding admin accounts.
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ? AND role != 'admin'");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = 'User not found or is an admin';
    header("Location: manage_users.php");
    exit();
}

// Retrieve any flash messages from the session.
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect submitted form values for validation.
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    $errors = [];
    if (strlen($name) < 2 || strlen($name) > 100)
        $errors[] = 'Name must be 2-100 characters';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Invalid email format';

    // Ensure the updated email address is unique across non-admin users.
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'Email already exists';
    }

    if (empty($errors)) {
        // Apply the changes to the user record.
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'User updated successfully';
            header("Location: manage_users.php");
            exit();
        } else {
            $error = 'Failed to update user';
        }
    } else {
        // Aggregate validation errors for display.
        $error = implode(', ', $errors);
    }
}
?>

<link rel="stylesheet" href="../assets/css/admin.css">

<div class="admin-container">
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="admin-header">
        <h1>Edit User #<?= $user['id'] ?></h1>
        <a href="manage_users.php" class="btn btn-secondary">â† Back to Users</a>
    </div>

    <form method="POST" class="admin-form">
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                maxlength="100">
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-actions">
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" name="update" class="btn btn-primary">Update User</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
