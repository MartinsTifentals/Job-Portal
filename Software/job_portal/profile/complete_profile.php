<?php

// Job Portal file: profile\complete_profile.php

error_reporting(0);

ini_set('display_errors', 0);

ob_start();



session_start();

require_once '../includes/db.php';



// Ensure only JSON is returned from this endpoint.
header_remove();

header('Content-Type: application/json');



$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {

    // Reject requests from anonymous users to prevent unauthorized profile updates.
    echo json_encode(['success' => false, 'message' => 'No session']);

    ob_end_flush();

    exit();

}



// Sanitize user-submitted text fields before saving to the database.
$bio = isset($_POST['bio']) ? mysqli_real_escape_string($conn, $_POST['bio']) : '';

$location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';

$phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';

$skills = isset($_POST['skills']) ? mysqli_real_escape_string($conn, $_POST['skills']) : '';

$education = isset($_POST['education']) ? mysqli_real_escape_string($conn, $_POST['education']) : '';

$experience = isset($_POST['experience']) ? mysqli_real_escape_string($conn, $_POST['experience']) : '';

$links = isset($_POST['links']) ? mysqli_real_escape_string($conn, $_POST['links']) : '';



$currentProfilePicture = null;

$currentCvFile = null;



// Load existing file paths so we only overwrite them when a new upload is provided.
$current_stmt = $conn->prepare("SELECT profile_picture, cv_file FROM users WHERE id = ?");

$current_stmt->bind_param("i", $user_id);

$current_stmt->execute();

$current_result = $current_stmt->get_result();

if ($current_row = $current_result->fetch_assoc()) {

    $currentProfilePicture = $current_row['profile_picture'];

    $currentCvFile = $current_row['cv_file'];

}

$current_stmt->close();



$profile_dir = "../uploads/profile_picture/$user_id/";

$cv_dir = "../uploads/cv/$user_id/";

$legacy_profile_assets_dir = "../assets/uploads/profile_pictures/";

@mkdir($profile_dir, 0777, true);

@mkdir($cv_dir, 0777, true);

@mkdir($legacy_profile_assets_dir, 0777, true);



$profile_picture = $currentProfilePicture;

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {

    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);

    $ext = strtolower($ext);



    // Accept only image file uploads for profile pictures.
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {

        $new_name = uniqid('profile_') . '.' . $ext;

        $target = $profile_dir . $new_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {

            @copy($target, $legacy_profile_assets_dir . $new_name);

            $profile_picture = $new_name;

        }

    }

}



$cv_file = $currentCvFile;

if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] == 0) {

    $ext = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);

    $ext = strtolower($ext);



    // Accept only document uploads for CV files.
    if (in_array($ext, ['pdf', 'doc', 'docx'])) {

        $new_name = uniqid('cv_') . '.' . $ext;

        $target = $cv_dir . $new_name;



        if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $target)) {

            $cv_file = "uploads/cv/$user_id/$new_name";

        }

    }

}



// Update the user's profile data and mark onboarding as complete.
$sql = "UPDATE users SET

    profile_picture='$profile_picture',

    cv_file='$cv_file',

    bio='$bio',

    location='$location',

    phone='$phone',

    skills='$skills',

    education='$education',

    experience='$experience',

    links='$links',

    onboarding_completed=1

WHERE id=$user_id";



if (mysqli_query($conn, $sql)) {

    // Return success to the frontend as JSON.
    echo json_encode(['success' => true, 'message' => 'Profile completed!']);

} else {

    // Return a JSON error on database failure.
    echo json_encode(['success' => false, 'message' => 'DB Error']);

}



ob_end_flush();

?>





