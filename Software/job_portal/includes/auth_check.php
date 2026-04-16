<?php
// Job Portal file: includes\auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function auth_check_output_fallback($title, $message, $primaryLabel, $primaryHref)
{
    echo '<div style="max-width:760px;margin:28px auto;padding:0 16px;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px;box-shadow:0 4px 14px rgba(0,0,0,.04);">
                <h3 style="margin:0 0 8px;color:#111827;font-size:1.15rem;">' . htmlspecialchars($title) . '</h3>
                <p style="margin:0 0 14px;color:#4b5563;line-height:1.5;">' . htmlspecialchars($message) . '</p>
                <a href="' . htmlspecialchars($primaryHref) . '" style="display:inline-block;background:#6d28d9;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:600;">' . htmlspecialchars($primaryLabel) . '</a>
            </div>
          </div>';
}

if (!isset($_SESSION['user_id'])) {
    $loginPath = "/Job_Portal/authentication/signup.php";

    if (!headers_sent()) {
        header("Location: " . $loginPath);
        exit();
    }

    auth_check_output_fallback(
        "Login required",
        "You must be signed in to access this page.",
        "Go to Signup",
        $loginPath
    );
    exit();
}

include 'db.php';

$user_id = (int) $_SESSION['user_id'];
$check_onboarding = "SELECT onboarding_completed FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $check_onboarding);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (!$user['onboarding_completed'] && basename($_SERVER['PHP_SELF']) !== 'onboarding.php') {
        $onboardingPath = "/Job_Portal/profile/onboarding.php";

        if (!headers_sent()) {
            header("Location: " . $onboardingPath);
            exit();
        }

        auth_check_output_fallback(
            "Complete onboarding",
            "Your account setup is not complete yet. Please finish onboarding to continue.",
            "Continue Onboarding",
            $onboardingPath
        );
        exit();
    }
}
?>

