<?php
include 'includes/db.php';
include 'includes/header.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM jobs";

if($keyword){
    $sql .= " WHERE title LIKE '%$keyword%' OR company LIKE '%$keyword%'";
}

$result = $conn->query($sql);
?>

<div class="container my-5">
<h2>Browse Jobs</h2>

<?php while($job = $result->fetch_assoc()): ?>

<div class="card p-3 mb-3">
  <h5><?= $job['title'] ?></h5>
  <p><?= $job['company'] ?> • <?= $job['location'] ?></p>
  <a href="job.php?id=<?= $job['id'] ?>">View Details</a>
</div>

<?php endwhile; ?>

</div>

<?php include 'includes/footer.php'; ?>
