<?php
session_start();
include("db.php");
include("secure.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$dob = isset($_POST['dob']) ? $_POST['dob'] : null;
$photo_path = null;

$sql = "SELECT photograph FROM users WHERE name = '" . mysqli_real_escape_string($conn, $username) . "'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
$old_photo_path = $user['photograph'] ?? null;


if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $file_size = $_FILES['photo']['size'];
    $file_type = $_FILES['photo']['type'];
    $file_name = $_FILES['photo']['name'];

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($file_type, $allowed_types)) {
        header("Location: welcome.php?error=" . urlencode("Invalid file type. Only JPG, PNG, GIF allowed."));
        exit();
    }

    if ($file_size < 20 * 1024 || $file_size > 500 * 1024) {
        header("Location: welcome.php?error=" . urlencode("File must be between 20KB and 500KB. Uploaded file: $file_name"));
        exit();
    }

    $upload_folder = "uploads/";
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    
    if (!empty($old_photo_path) && file_exists($old_photo_path)) {
        unlink($old_photo_path);
    }

    
    $photo_path = $upload_folder . time() . "_" . basename($file_name);
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
        header("Location: welcome.php?error=" . urlencode("Failed to upload file: $file_name"));
        exit();
    }
}

$update_fields = [];

if (!empty($dob)) {
    $update_fields[] = "dob = '" . mysqli_real_escape_string($conn, $dob) . "'";
}

if (!empty($photo_path)) {
    $update_fields[] = "photograph = '" . mysqli_real_escape_string($conn, $photo_path) . "'";
}

if (!empty($update_fields)) {
    $update_sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE name = '" . mysqli_real_escape_string($conn, $username) . "'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: welcome.php");
        exit();
    } else {
        header("Location: welcome.php?error=" . urlencode("Error updating profile: " . mysqli_error($conn)));
        exit();
    }
} else {
    header("Location: welcome.php?error=" . urlencode("Nothing to update."));
    exit();
}
?>
