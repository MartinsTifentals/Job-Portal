<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JobMatrix</title>
  <link rel="stylesheet" href="/Job_Portal/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;60;700&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <!-- Menu Toggle Button (Left Side) -->
    <button id="menu-toggle" class="menu-toggle">
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>

    <div id="menu-overlay" class="menu-overlay">
      <nav class="menu">
        <!-- Menu Header with Logo -->
        <div class="menu-header">
          <a href="/Job_Portal/index.php" class="logo">
            <img src="/Job_Portal/assets/images/logo1.png" alt="logo1">
          </a>
        </div>

        <ul class="menu-list">
          <li class="menu-list-item">
            <a href="/Job_Portal/job/browse.php" class="menu-link">
              <span class="eyebrow">01</span>
              <span class="menu-link-heading">Jobs</span>
              <div class="menu-link-bg"></div>
            </a>
          </li>
          <li class="menu-list-item">
            <a href="/Job_Portal/job/job_categories.php" class="menu-link">
              <span class="eyebrow">02</span>
              <span class="menu-link-heading">Categories</span>
              <div class="menu-link-bg"></div>
            </a>
          </li>
          <li class="menu-list-item">
            <a href="/Job_Portal/live-chat" class="menu-link">
              <span class="eyebrow">03</span>
              <span class="menu-link-heading">Live Chat</span>
              <div class="menu-link-bg"></div>
            </a>
          </li>
          <li class="menu-list-item">
            <a href="/Job_Portal/pages/faq.php" class="menu-link">
              <span class="eyebrow">04</span>
              <span class="menu-link-heading">FAQ</span>
              <div class="menu-link-bg"></div>
            </a>
          </li>
          <li class="menu-list-item">
            <a href="/Job_Portal/settings/settings.php" class="menu-link">
              <span class="eyebrow">05</span>
              <span class="menu-link-heading">Settings</span>
              <div class="menu-link-bg"></div>
            </a>
          </li>
        </ul>

        <div class="menu-details">
          <span class="p-small">Get Involved</span>
          <div class="socials-row">
            <a href="https://www.instagram.com/" class="text-link">Instagram</a>
            <a href="https://en-gb.facebook.com/" class="text-link">Facebook</a>
            <a href="https://truthsocial.com/" class="text-link">Truth</a>
            <a href="https://x.com/" class="text-link">Twitter</a>
            <a href="/Job_Portal/donate.php" class="text-link">Donate</a>
          </div>
        </div>
      </nav>
    </div>


    <div class="logo">
      <a href="/Job_Portal/index.php">JobMatrix</a>
    </div>

    <nav>
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="profile-wrapper">
          <?php
          include_once $_SERVER['DOCUMENT_ROOT'] . "/Job_Portal/includes/db.php";
          $user_id = $_SESSION['user_id'];

          $query = mysqli_query($conn, "SELECT profile_picture FROM users WHERE id=$user_id");
          $user = mysqli_fetch_assoc($query);

          $profilePic = "/Job_Portal/assets/images/profile_icon.png"; // default image
        
          if (!empty($user['profile_picture'])) {
            $profilePic = "/Job_Portal/assets/uploads/profile_pictures/" . $user['profile_picture'];
          }
          ?>

          <img src="<?php echo $profilePic; ?>" class="profile-avatar">
          <div class="profile-button" id="profileToggle">+</div>
          <div class="profile-menu" id="profileMenu">
            <a href="/Job_Portal/profile/profile.php">Profile</a>
            <a href="/Job_Portal/job/my_applications.php">Applications</a>
            <?php
            include_once $_SERVER['DOCUMENT_ROOT'] . "/Job_Portal/includes/db.php";
            $user_id = $_SESSION['user_id'];
            $query = mysqli_query($conn, "SELECT role FROM users WHERE id='$user_id'");
            $user = mysqli_fetch_assoc($query);
            if ($user && $user['role'] === 'admin') {
              echo '<a href="/Job_Portal/admin/dashboard.php">Admin Panel</a>';
            }
            ?>
            <a href="/Job_Portal/authentication/logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="/Job_Portal/authentication/login.php" class="login-btn" id="loginBtn">Login</a>
      <?php endif; ?>
    </nav>

  </header>

  <!-- LOGIN MODAL -->
  <div class="modal-overlay" id="loginModal">
    <div class="modal-content">
      <button class="modal-close" id="closeModal">X</button>
      <h3>Sign In To Job Matrix</h3>
      <p class="modal-subtitle">Welcome Back! Please Login to Your Account</p>

      <?php if (isset($_GET['error'])): ?>
        <p style="color:red; text-align:center;">
          <?php
          if ($_GET['error'] == 'wrongpassword') {
            echo "Incorrect password.";
          }
          if ($_GET['error'] == 'nouser') {
            echo "User not found.";
          }
          ?>
        </p>
      <?php endif; ?>


      <form class="login-form" method="POST" action="/Job_Portal/authentication/login.php">

        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Enter your email address" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>

        <button type="submit" class="continue-btn">Login</button>

      </form>


      <p class="modal-footer-text">
        Don't Have An Account? <a href="authentication/signup.php">Sign Up</a>
      </p>

    </div>
  </div>

</body>
<script src="/Job_Portal/scripts/script.js"></script>

</html>