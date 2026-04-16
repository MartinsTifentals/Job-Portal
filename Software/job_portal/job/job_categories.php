<?php
include '../includes/db.php';
include '../includes/header.php';

// Fetch distinct categories and counts from DB
$query = "SELECT category, COUNT(*) AS total 
          FROM jobs 
          WHERE category IS NOT NULL AND category <> '' 
          GROUP BY category 
          ORDER BY category";
$result = mysqli_query($conn, $query);

$categories = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = [
      'name' => $row['category'],
      'count' => (int) $row['total']
    ];
  }
}

// Function to split categories into two columns
function splitTwoCols($arr)
{
  $half = (int) ceil(count($arr) / 2);
  return [array_slice($arr, 0, $half), array_slice($arr, $half)];
}

// Function to build category links
function catHref($cat)
{
  if ($cat === "All Categories")
    return "browse.php";
  return "browse.php?category=" . urlencode($cat);
}
?>

<style>
  /* Use your CSS from before, kept clean for table layout */
  :root {
    --home-purple: #6d28d9;
    --home-purple-dark: #5b21b6;
    --home-purple-soft: #f4efff;
    --home-bg: #f5f6fb;
    --panel-bg: #ffffff;
    --text-main: #1f2937;
    --line: rgba(31, 41, 55, 0.08);
    --shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
  }

  .cat-page {
    background: var(--home-bg);
    min-height: 100vh;
    padding: 34px 0 70px;
  }

  .cat-wrap {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .section-title {
    background: var(--home-purple);
    color: #fff;
    border-radius: 14px;
    padding: 13px 16px;
    font-weight: 900;
    margin: 0 0 12px;
  }

  .table-wrap {
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid var(--line);
    box-shadow: var(--shadow);
    background: #fff;
    margin-bottom: 22px;
  }

  .table-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
  }

  @media(max-width: 900px) {
    .table-grid {
      grid-template-columns: 1fr;
    }
  }

  .col {
    border-right: 1px solid var(--line);
  }

  .col:last-child {
    border-right: none;
  }

  .row-item {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--line);
    text-decoration: none;
    color: var(--text-main);
    background: #fff;
    transition: .18s ease;
    min-height: 52px;
  }

  .row-item:nth-child(odd) {
    background: #fcfbff;
  }

  .row-item:hover {
    background: var(--home-purple-soft);
  }

  .cat-name {
    font-weight: 800;
    font-size: 14px;
    line-height: 1.35;
  }

  .cat-count {
    color: var(--home-purple);
    font-weight: 900;
    min-width: 24px;
    text-align: right;
  }

  .muted {
    color: #6b7280;
    font-size: 13px;
    margin-top: 6px;
    line-height: 1.5;
  }
</style>

<div class="cat-page">
  <div class="cat-wrap">
    <div class="section-title">Job Categories</div>
    <div class="table-wrap">
      <div class="table-grid">
        <?php
        [$col1, $col2] = splitTwoCols($categories);
        ?>
        <div class="col">
          <?php foreach ($col1 as $cat): ?>
            <a class="row-item" href="<?= htmlspecialchars(catHref($cat['name'])) ?>">
              <span class="cat-name"><?= htmlspecialchars($cat['name']) ?></span>
              <span class="cat-count"><?= $cat['count'] ?></span>
            </a>
          <?php endforeach; ?>
        </div>
        <div class="col">
          <?php foreach ($col2 as $cat): ?>
            <a class="row-item" href="<?= htmlspecialchars(catHref($cat['name'])) ?>">
              <span class="cat-name"><?= htmlspecialchars($cat['name']) ?></span>
              <span class="cat-count"><?= $cat['count'] ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>