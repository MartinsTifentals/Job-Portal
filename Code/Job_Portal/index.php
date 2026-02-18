<?php
include 'includes/db.php';
include 'includes/header.php';


//Get Current Page
$jobsPerPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $jobsPerPage;

//Count Total Jobs
$totalQuery = "SELECT COUNT(*) as total FROM jobs";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalJobs = $totalRow['total'];

$totalPages = ceil($totalJobs / $jobsPerPage);


// Fetch Jobs with Pagination
// Promoted jobs appear first
$jobsQuery = "SELECT * FROM jobs 
              ORDER BY is_promoted DESC, created_at DESC 
              LIMIT $jobsPerPage OFFSET $offset";
$jobsResult = mysqli_query($conn, $jobsQuery);


// Total active jobs for stats/banner
$activeResult = $conn->query("SELECT COUNT(*) AS total_jobs FROM jobs");
$activeRow = $activeResult->fetch_assoc();
$total_active_jobs = $activeRow['total_jobs'];
?>

<!-- BANNER -->
<section class="banner">
    <h1>Over <?php echo number_format($total_active_jobs); ?>+ Jobs to Apply</h1>
    <p>Your Next Big Career Move Starts Here. Explore The Best Job Opportunities For Your Future!</p>
    <div class="search-form">
        <input type="text" placeholder="Search for Jobs">
        <input type="text" placeholder="Location">
        <button>Search</button>
    </div>
</section>

<!-- STATS -->
<section class="stats">
    <div class="stats-container">

        <div class="stat-card">
            <i class="fas fa-briefcase"></i>
            <h3><?php echo number_format($total_active_jobs); ?>+</h3>
            <p>Active Jobs</p>
        </div>

        <div class="stat-card">
            <i class="fas fa-building"></i>
            <h3>5,000+</h3>
            <p>Companies</p>
        </div>

        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3>50,000+</h3>
            <p>Job Seekers</p>
        </div>

        <div class="stat-card">
            <i class="fas fa-chart-line"></i>
            <h3>8,500+</h3>
            <p>Jobs Filled</p>
        </div>

    </div>
</section>

<!-- MAIN CONTENT -->
<div class="main-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="filter-section">
            <h3>Search By Category</h3>
            <form>
                <label><input type="checkbox">Cybersecurity</label>
                <label><input type="checkbox">Data Science</label>
                <label><input type="checkbox">Designing</label>
                <label><input type="checkbox">Management</label>
                <label><input type="checkbox">Networking</label>
                <label><input type="checkbox">Programming</label>
            </form>
        </div>
        <div class="filter-section">
            <h3>Search By Location</h3>
            <form>
                <label><input type="checkbox">Bradford</label>
                <label><input type="checkbox">Doncaster</label>
                <label><input type="checkbox">Huddersfield</label>
                <label><input type="checkbox">Leeds</label>
                <label><input type="checkbox">Manchester</label>
                <label><input type="checkbox">Sheffield</label>
            </form>
        </div>
    </aside>

    <!-- JOBS -->
    <section class="latest-jobs">
        <h2>Promoted Jobs</h2>
        <p>Get your job from the best companies</p>

        <div class="job-cards">
            <?php while($job = mysqli_fetch_assoc($jobsResult)) { ?>
                <div class="job-card">
                    <h3><?php echo $job['title']; ?></h3>
                    <p class="level">
                        <?php echo $job['location']; ?> • <?php echo $job['category']; ?>
                    </p>
                    <p class="desc">
                        <?php echo substr($job['description'], 0, 120); ?>...
                    </p>
                    <div class="action">
                        <a href="job.php?id=<?php echo $job['id']; ?>" class="learn">View Job</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Step 4 — Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo;</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                   class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">&raquo;</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- LOGIN MODAL -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModal">X</button>
            <h3>Sign In To Job Matrix</h3>
            <p class="modal-subtitle">Welcome Back! Please Login to Your Account</p>

            <button class="google-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">Continue With Google
            </button>

            <div class="divider"><span>or</span></div>

            <form class="login-form">
                <label for="email">Email Address</label>
                <input type="email" id="email" placeholder="Enter your email address" required>
                <button type="submit" class="continue-btn">Continue</button>
            </form>

            <p class="modal-footer-text">
                Don't Have An Account <a href="#">Sign Up</a>
            </p>

        </div>
    </div>
    <script src="scripts/script.js"></script>
</div>

<?php include 'includes/footer.php'; ?>
