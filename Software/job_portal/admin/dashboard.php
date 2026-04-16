<?php
// Include authentication and authorization checks
include '../includes/auth_check.php'; // Check if the user is logged in
include '../includes/admin_check.php'; // Check if the user has admin privileges
include '../includes/db.php'; // Include database connection
include '../includes/header.php'; // Include the page header
 
try {
    // Initialize an array to hold the statistics data
    $stats = [
        'users' => 0, // Total number of users
        'jobs' => 0, // Total number of jobs
        'promoted' => 0, // Total number of promoted jobs
        'pending_users' => 0, // Number of users still in onboarding process
        'pending_jobs' => 0 // Number of jobs that are pending approval
    ];
 
    // Query to get the total number of users from the database
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute(); // Execute the query
    // Store the result in the 'users' field of the stats array
    $stats['users'] = $stmt->get_result()->fetch_assoc()['total'];
 
    // Query to get the total number of jobs from the database
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM jobs");
    $stmt->execute(); // Execute the query
    // Store the result in the 'jobs' field of the stats array
    $stats['jobs'] = $stmt->get_result()->fetch_assoc()['total'];
 
    // Query to get the total number of promoted jobs from the database
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM jobs WHERE is_promoted = 1");
    $stmt->execute(); // Execute the query
    // Store the result in the 'promoted' field of the stats array
    $stats['promoted'] = $stmt->get_result()->fetch_assoc()['total'];
 
    // Query to get the number of users who have not completed onboarding
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE onboarding_completed = 0");
    $stmt->execute(); // Execute the query
    // Store the result in the 'pending_users' field of the stats array
    $stats['pending_users'] = $stmt->get_result()->fetch_assoc()['total'];
 
    // Query to get the number of jobs that are pending approval
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM jobs WHERE approval_status = 'pending'");
    $stmt->execute(); // Execute the query
    // Store the result in the 'pending_jobs' field of the stats array
    $stats['pending_jobs'] = $stmt->get_result()->fetch_assoc()['total'];
 
} catch (Exception $e) {
    // If there is an exception (error) during the database queries, log the error
    error_log("Dashboard stats error: " . $e->getMessage());
    // Set all statistics to zero in case of an error
    $stats = ['users' => 0, 'jobs' => 0, 'promoted' => 0, 'pending_users' => 0, 'pending_jobs' => 0];
}
?>
 
<!-- Link to the stylesheet for the admin dashboard layout -->
<link rel="stylesheet" href="../assets/css/admin.css">
 
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Admin Dashboard</h1> <!-- Main heading for the admin dashboard -->
            <!-- Display the current date and time when the page was last updated -->
            <p>Updated <?= date('M j, Y \a\t g:i A') ?></p>
        </div>
        <div class="btn-group">
            <!-- Button to navigate to the "Manage Jobs" page -->
            <a href="manage_jobs.php" class="btn btn-primary">Manage Jobs</a>
            <!-- Button to navigate to the "Manage Users" page -->
            <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
        </div>
    </div>
 
    <!-- Admin statistics section -->
    <div class="admin-stats">
        <!-- Display total number of users -->
        <div class="stat-card">
            <h3><?= number_format($stats['users']) ?></h3>
            <p>Total Users</p>
        </div>
        <!-- Display total number of jobs -->
        <div class="stat-card">
            <h3><?= number_format($stats['jobs']) ?></h3>
            <p>Total Jobs</p>
        </div>
        <!-- Display total number of promoted jobs -->
        <div class="stat-card">
            <h3><?= number_format($stats['promoted']) ?></h3>
            <p>Promoted Jobs</p>
        </div>
        <!-- Display number of users with pending onboarding -->
        <div class="stat-card">
            <h3><?= number_format($stats['pending_users']) ?></h3>
            <p>Pending Onboarding</p>
        </div>
        <!-- Display number of jobs pending approval -->
        <div class="stat-card">
            <h3><?= number_format($stats['pending_jobs']) ?></h3>
            <p>Pending Job Reviews</p>
        </div>
    </div>
</div>
 
<!-- Include the footer for the page -->
<?php include '../includes/footer.php'; ?>