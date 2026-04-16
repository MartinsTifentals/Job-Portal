<?php
include 'includes/db.php';
include 'includes/header.php';

// Get filters from GET (arrays for multi-select)
$search = trim($_GET['search'] ?? '');
$locations = $_GET['location'] ?? []; // array of selected locations
$categories = $_GET['category'] ?? []; // array of selected categories

if (!is_array($locations))
    $locations = $locations ? [$locations] : [];
if (!is_array($categories))
    $categories = $categories ? [$categories] : [];

// Pagination
$jobsPerPage = 12;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
$offset = ($page - 1) * $jobsPerPage;

// Build WHERE clause for promoted jobs
$where = "WHERE is_promoted = 1";

if ($search !== '') {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where .= " AND title LIKE '%$search_safe%'";
}

if (!empty($locations)) {
    $loc_safe = array_map(fn($l) => "'" . mysqli_real_escape_string($conn, $l) . "'", $locations);
    $where .= " AND location IN (" . implode(',', $loc_safe) . ")";
}

if (!empty($categories)) {
    $cat_safe = array_map(fn($c) => "'" . mysqli_real_escape_string($conn, $c) . "'", $categories);
    $where .= " AND category IN (" . implode(',', $cat_safe) . ")";
}

// Count total promoted jobs for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM jobs $where";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalJobs = $totalRow['total'];
$totalPages = max(1, ceil($totalJobs / $jobsPerPage));

// Fetch promoted jobs with filters
$jobsQuery = "SELECT * FROM jobs $where ORDER BY created_at DESC LIMIT $jobsPerPage OFFSET $offset";
$jobsResult = mysqli_query($conn, $jobsQuery);

// Fetch distinct categories & locations from promoted jobs only
$categoryResult = mysqli_query($conn, "SELECT DISTINCT category FROM jobs WHERE is_promoted = 1 AND category IS NOT NULL AND category<>'' ORDER BY category");
$allCategories = [];
while ($cat = mysqli_fetch_assoc($categoryResult))
    $allCategories[] = $cat['category'];

$locationResult = mysqli_query($conn, "SELECT DISTINCT location FROM jobs WHERE is_promoted = 1 AND location IS NOT NULL AND location<>'' ORDER BY location");
$allLocations = [];
while ($loc = mysqli_fetch_assoc($locationResult))
    $allLocations[] = $loc['location'];

// Total active jobs for banner
$activeResult = $conn->query("SELECT COUNT(*) AS total_jobs FROM jobs");
$activeRow = $activeResult->fetch_assoc();
$total_active_jobs = $activeRow['total_jobs'];
?>

<!-- BANNER -->
<section class="banner">
    <h1>Over <?= number_format($total_active_jobs) ?>+ Jobs to Apply</h1>
    <p>Your Next Big Career Move Starts Here. Explore The Best Job Opportunities For Your Future!</p>
    <div class="search-form">
        <form class="search-form" action="job/browse.php" method="GET">
            <input type="text" name="search" placeholder="Search for Jobs" value="<?= htmlspecialchars($search) ?>">
            <input type="text" name="location" placeholder="Location"
                value="<?= htmlspecialchars($locations[0] ?? '') ?>">
            <button type="submit">Search</button>
        </form>
    </div>
</section>


<!-- STATS -->
<section class="stats">
    <div class="carousel">
        <div class="group">
            <?php $stats = [['icon' => 'fas fa-briefcase', 'count' => $total_active_jobs, 'label' => 'Active Jobs'], ['icon' => 'fas fa-building', 'count' => '5,000+', 'label' => 'Companies'], ['icon' => 'fas fa-users', 'count' => '50,000+', 'label' => 'Job Seekers'], ['icon' => 'fas fa-chart-line', 'count' => '8,500+', 'label' => 'Jobs Filled'], ['icon' => 'fas fa-lightbulb', 'count' => '300+', 'label' => 'New Jobs This Month'], ['icon' => 'fas fa-star', 'count' => '4.8/5', 'label' => 'User Satisfaction'], ['icon' => 'fas fa-graduation-cap', 'count' => '8,200+', 'label' => 'Internships Posted'], ['icon' => 'fas fa-envelope', 'count' => '14,800+', 'label' => 'Applications Submitted'],];
            for ($i = 0; $i < 3; $i++) {
                foreach ($stats as $stat) {
                    echo '<div class="stat-card">';
                    echo '<i class="' . $stat['icon'] . '"></i>';
                    echo '<h3>' . $stat['count'] . '</h3>';
                    echo '<p>' . $stat['label'] . '</p>';
                    echo '</div>';
                }
            } ?>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="main-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <form method="GET">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <div class="filter-section">
                <h3>Filter by Category</h3>
                <?php foreach ($allCategories as $cat): ?>
                    <label>
                        <input type="checkbox" name="category[]" value="<?= htmlspecialchars($cat) ?>" <?= in_array($cat, $categories) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="filter-section">
                <h3>Filter by Location</h3>
                <?php foreach ($allLocations as $loc): ?>
                    <label>
                        <input type="checkbox" name="location[]" value="<?= htmlspecialchars($loc) ?>" <?= in_array($loc, $locations) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($loc) ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <button type="submit">Apply Filters</button>
        </form>
    </aside>

    <!-- JOBS -->
    <section class="latest-jobs">
        <h2>Promoted Jobs</h2>
        <p>Get your job from the best companies</p>

        <div class="job-cards">
            <?php if (mysqli_num_rows($jobsResult) > 0): ?>
                <?php while ($job = mysqli_fetch_assoc($jobsResult)): ?>
                    <div class="job-card">
                        <h3><?= htmlspecialchars($job['title']) ?></h3>
                        <p class="level"><?= htmlspecialchars($job['company']) ?> • <?= htmlspecialchars($job['location']) ?> •
                            <?= htmlspecialchars($job['category']) ?>
                        </p>
                        <p class="desc"><?= substr(htmlspecialchars($job['description']), 0, 120) ?>...</p>
                        <div class="action">
                            <a href="job/job_details.php?id=<?= $job['id'] ?>" class="learn">View Job</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No promoted jobs found matching your filters.</p>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?><?= !empty($categories) ? '&' . http_build_query(['category' => $categories]) : '' ?><?= !empty($locations) ? '&' . http_build_query(['location' => $locations]) : '' ?>"
                    class="<?= ($i === $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>