<?php

session_start();
include "../includes/db.php";

// Redirect users who are not logged in.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Use the authenticated user ID for upload storage and profile updates.
$user_id = $_SESSION['user_id'];

if (isset($_FILES['cv_file'])) {
    // Gather file upload details for validation and storage.
    $file = $_FILES['cv_file'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ["pdf", "doc", "docx"];

    // Only allow allowed file extensions for CV uploads.
    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            // Enforce a maximum file size of 5 MB.
            if ($fileSize < 5 * 1024 * 1024) { 
                $upload_dir = "../uploads/cv/" . $user_id . "/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $newFileName = uniqid("cv_") . "." . $fileExt;
                $uploadPath = $upload_dir . $newFileName;

                // Move uploaded file into the user's CV directory.
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    $cv_path = "uploads/cv/" . $user_id . "/" . $newFileName;

                    // Persist the stored file path in the user's profile record.
                    $update = "UPDATE users SET cv_file = ? WHERE id = ?";
                    $stmt = $conn->prepare($update);
                    $stmt->bind_param("si", $cv_path, $user_id);
                    $stmt->execute();

                    header("Location: profile.php?cv=uploaded&success=1");
                    exit();
                } else {
                    $_SESSION['error'] = "Error uploading file.";
                }
            } else {
                $_SESSION['error'] = "File too large. Max size is 5MB.";
            }
        } else {
            $_SESSION['error'] = "Upload error.";
        }
    } else {
        $_SESSION['error'] = "Invalid file type. Only PDF, DOC, and DOCX allowed.";
    }
}

header("Location: profile.php?error=1");
exit();
?>

