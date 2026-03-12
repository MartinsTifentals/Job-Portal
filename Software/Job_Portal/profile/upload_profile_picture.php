<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif"];
    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) {
                $newFileName = "profile_" . $user_id . "." . $fileExt;
                $uploadPath = "../assets/uploads/profile_pictures/" . $newFileName;
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    $update = "UPDATE users SET profile_picture='$newFileName' WHERE id='$user_id'";
                    mysqli_query($conn, $update);
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "File too large. Max size is 5MB.";
            }
        } else {
            echo "Upload error.";
        }
    } else {
        echo "Only JPG, JPEG, PNG, and GIF allowed.";
    }
}
?>