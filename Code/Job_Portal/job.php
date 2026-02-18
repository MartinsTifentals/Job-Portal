<?php
include 'includes/db.php';
include 'includes/header.php';

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM jobs WHERE id=$id");
$job = $result->fetch_assoc();
?>

<div class="container my-5">

<h2><?= $job['title'] ?></h2>
<p><strong><?= $job['company'] ?></strong></p>

<p>
<?= $job['location'] ?><br>
<?= $job['salary'] ?><br>
<?= $job['type'] ?>
</p>

<hr>

<p><?= $job['description'] ?></p>

</div>

<?php include 'includes/footer.php'; ?>
