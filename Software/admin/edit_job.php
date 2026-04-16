<?php
// Include necessary files for authentication, admin check, database connection, and header
include '../includes/auth_check.php'; // Check if the user is logged in
include '../includes/admin_check.php'; // Check if the user has admin privileges
include '../includes/db.php'; // Include database connection
include '../includes/header.php'; // Include page header
 
// Get the job ID from the URL and validate it as an integer
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
 
// If the ID is not valid, set an error message and redirect to the jobs management page
if (!$id) {
    $_SESSION['error'] = 'Invalid job ID';
    header("Location: manage_jobs.php");
    exit(); // Ensure the script stops execution after redirect
}
 
// Query the database to get the job details based on the provided ID
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
 
// If the job doesn't exist, set an error message and redirect to the jobs management page
if (!$job) {
    $_SESSION['error'] = 'Job not found';
    header("Location: manage_jobs.php");
    exit(); // Ensure the script stops execution after redirect
}
 
// Retrieve any success or error messages from the session and clear them
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
 
// Process the form submission to update the job details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form input values and trim unnecessary spaces
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $is_promoted = isset($_POST['is_promoted']) ? 1 : 0; // Check if the job should be promoted
 
    // Array to store validation errors
    $errors = [];
   
    // Validate input values and add errors to the array if necessary
    if (strlen($title) < 3 || strlen($title) > 255)
        $errors[] = 'Title must be 3-255 characters';
    if (strlen($location) < 2 || strlen($location) > 100)
        $errors[] = 'Location must be 2-100 characters';
    if (strlen($category) < 2 || strlen($category) > 50)
        $errors[] = 'Category must be 2-50 characters';
    if (strlen($description) < 10)
        $errors[] = 'Description must be at least 10 characters';
 
    // If there are no validation errors, update the job in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE jobs SET title = ?, location = ?, category = ?, description = ?, is_promoted = ? WHERE id = ?");
        $stmt->bind_param("sssisi", $title, $location, $category, $description, $is_promoted, $id);
 
        // If the update is successful, set a success message and redirect to the jobs management page
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Job updated successfully';
            header("Location: manage_jobs.php");
            exit(); // Ensure the script stops execution after redirect
        } else {
            // If the update fails, set an error message
            $error = 'Failed to update job';
        }
    } else {
        // If there are validation errors, display them
        $error = implode(', ', $errors);
    }
}
?>
 
<!-- Include the stylesheet for the admin dashboard layout -->
<link rel="stylesheet" href="../assets/css/admin.css">
 
<div class="admin-container">
    <!-- Display success or error messages if set -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
 
    <!-- Admin header with job editing information -->
    <div class="admin-header">
        <h1>Edit Job #<?= $job['id'] ?></h1>
        <!-- Link to go back to the jobs management page -->
        <a href="manage_jobs.php" class="btn btn-secondary">← Back to Jobs</a>
    </div>
 
    <!-- Job editing form -->
    <form method="POST" class="admin-form">
        <!-- Job title input field -->
        <div class="form-group">
            <label for="title">Job Title *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($job['title']) ?>" required maxlength="255">
        </div>
 
        <!-- Job location input field -->
        <div class="form-group">
            <label for="location">Location *</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($job['location']) ?>" required maxlength="100">
        </div>
 
        <!-- Job category input field -->
        <div class="form-group">
            <label for="category">Category *</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($job['category']) ?>" required maxlength="50">
        </div>
 
        <!-- Job description textarea -->
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="6" required><?= htmlspecialchars($job['description']) ?></textarea>
        </div>
 
        <!-- Checkbox for promoting the job (featured listing) -->
        <div class="form-group">
            <label class="promote-checkbox">
                <input type="checkbox" name="is_promoted" <?= $job['is_promoted'] ? 'checked' : '' ?>>
                Promote this job (Featured listing)
            </label>
        </div>
 
        <!-- Form action buttons: Cancel and Update Job -->
        <div class="form-actions">
            <a href="manage_jobs.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" name="update" class="btn btn-primary">Update Job</button>
        </div>
    </form>
</div>
 
<!-- Include the footer for the page -->
<?php include '../includes/footer.php'; ?>