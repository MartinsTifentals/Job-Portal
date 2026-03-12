<?php
include '../includes/db.php';
include '../includes/header.php';

$jobsPerPage = 12;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $jobsPerPage;

// Get filters from GET
$search   = trim($_GET['search'] ?? '');
$location = trim($_GET['location'] ?? '');
$category = $_GET['category'] ?? ''; // single select now
$salary   = $_GET['salary'] ?? '';

$where = "WHERE 1=1";

// Search filter
if ($search !== '') {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where .= " AND title LIKE '%$search_safe%'";
}

// Location filter
if ($location !== '') {
    $location_safe = mysqli_real_escape_string($conn, $location);
    $where .= " AND location='$location_safe'";
}

// Category filter (single select)
if ($category !== '') {
    $category_safe = mysqli_real_escape_string($conn, $category);
    $where .= " AND category='$category_safe'";
}

// Salary filter
if ($salary !== '') {
    if ($salary == "low") {
        $where .= " AND salary < 30000";
    } elseif ($salary == "mid") {
        $where .= " AND salary BETWEEN 30000 AND 60000";
    } elseif ($salary == "high") {
        $where .= " AND salary > 60000";
    }
}

// Total jobs for pagination
$totalQuery  = "SELECT COUNT(*) as total FROM jobs $where";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow    = mysqli_fetch_assoc($totalResult);
$totalJobs   = $totalRow['total'] ?? 0;
$totalPages  = max(1, ceil($totalJobs / $jobsPerPage));

// Fetch jobs
$query  = "SELECT * FROM jobs $where ORDER BY created_at DESC LIMIT $jobsPerPage OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<div class="browse-container">
    <h1>Browse Jobs</h1>

    <form class="browse-search" method="GET">
        <div class="search-group">
            <input type="text" name="search" placeholder="Search jobs, companies..."
                   value="<?= htmlspecialchars($search) ?>">
        </div>

        <div class="search-group">
            <select name="location">
                <option value="">All Locations</option>
                <?php
                $locQuery  = "SELECT DISTINCT location FROM jobs ORDER BY location";
                $locResult = mysqli_query($conn, $locQuery);
                while ($loc = mysqli_fetch_assoc($locResult)): ?>
                    <option value="<?= htmlspecialchars($loc['location']) ?>"
                        <?= ($loc['location'] === $location) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($loc['location']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="search-group">
            <select name="category">
                <option value="">All Categories</option>
                <?php
                $catQuery  = "SELECT DISTINCT category FROM jobs ORDER BY category";
                $catResult = mysqli_query($conn, $catQuery);
                while ($cat = mysqli_fetch_assoc($catResult)): ?>
                    <option value="<?= htmlspecialchars($cat['category']) ?>"
                        <?= ($cat['category'] === $category) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="search-group">
            <select name="salary">
                <option value="">Salary Range</option>
                <option value="low"  <?= ($salary === "low")  ? 'selected' : '' ?>>£0 - £30k</option>
                <option value="mid"  <?= ($salary === "mid")  ? 'selected' : '' ?>>£30k - £60k</option>
                <option value="high" <?= ($salary === "high") ? 'selected' : '' ?>>£60k+</option>
            </select>
        </div>

        <button class="search-btn">Search Jobs</button>
    </form>

    <div class="job-cards">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($job = mysqli_fetch_assoc($result)): ?>
                <div class="job-card">
                    <?php if ($job['is_promoted']): ?>
                        <span class="badge">Promoted</span>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($job['title']) ?></h3>
                    <p class="level">
                        <?= htmlspecialchars($job['company']) ?> •
                        <?= htmlspecialchars($job['location']) ?> •
                        <?= htmlspecialchars($job['category']) ?>
                    </p>
                    <p class="salary">£<?= number_format($job['salary']) ?></p>
                    <p class="desc"><?= substr(htmlspecialchars($job['description']), 0, 120) ?>...</p>
                    <div class="action">
                        <a href="job_details.php?id=<?= $job['id'] ?>" class="learn">View Job</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No jobs found matching your criteria.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>&category=<?= urlencode($category) ?>&salary=<?= urlencode($salary) ?>">&laquo;</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>&category=<?= urlencode($category) ?>&salary=<?= urlencode($salary) ?>"
               class="<?= ($i === $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>&category=<?= urlencode($category) ?>&salary=<?= urlencode($salary) ?>">&raquo;</a>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>