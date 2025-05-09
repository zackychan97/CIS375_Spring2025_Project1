<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/project_functions.php';

requireLogin();

$user_id = $_SESSION['user_id'] ?? null;

// CAPTURE FORM DATA
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// VALIDATE FIELDS ARE NOT EMPTY
if (empty($name) || empty($email)) {
    flashMessage("Name and email are required.", "error");
    header("Location: edit_profile.php");
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flashMessage("Invalid email format.", "error");
    header("Location: edit_profile.php");
    exit();
}

// CHECK FOR PASSWORD CHANGE
if (!empty($new_password) || !empty($confirm_password)) {
    if ($new_password !== $confirm_password) {
        flashMessage("Passwords do not match.", "error");
        header("Location: edit_profile.php");
        exit();
    }
    if (strlen($new_password) < 6) {
        flashMessage("Password must be at least 6 characters.", "error");
        header("Location: edit_profile.php");
        exit();
    }

    // UPDATE WITH PASSWORD CHANGE
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $hashedPassword, $user_id);
} else {
    // UPDATE WITHOUT PASSWORD CHANGE
    $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);
}

// EXECUTE QUERY
if (mysqli_stmt_execute($stmt)) {
   //UPDATE SESSION VARIABLES
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    // —— proPic UPLOAD LOGIC ——
    if (isset($_FILES['proPic']) && $_FILES['proPic']['error'] === UPLOAD_ERR_OK) {
        $tmp      = $_FILES['proPic']['tmp_name'];
        $origName = $_FILES['proPic']['name'];
        $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','gif'];
        $maxSize  = 2 * 1024 * 1024; // 2 MB

        if (!in_array($ext, $allowed) || $_FILES['proPic']['size'] > $maxSize) {
            flashMessage('proPic must be JPG/PNG/GIF under 2 MB.', 'warning');
        } else {
            // UPLOAD DIR
            $uploadDir = __DIR__ . '/public/uploads/proPic/' . $user_id;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = 'profile.' . $ext;
            $dest     = $uploadDir . '/' . $fileName;

            if (move_uploaded_file($tmp, $dest)) {
                // UPDATE DB WITH PATH
                $relPath = 'proPic/' . $user_id . '/' . $fileName;
                $uStmt   = mysqli_prepare(
                    $conn,
                    "UPDATE users SET proPic = ? WHERE id = ?"
                );
                mysqli_stmt_bind_param($uStmt, 'si', $relPath, $user_id);
                mysqli_stmt_execute($uStmt);
                mysqli_stmt_close($uStmt);
            } else {
                flashMessage('Failed to upload profile picture.', 'error');
            }
        }
    }

    flashMessage("Profile updated successfully!", "success");
    header("Location: dashboard.php");
    exit();
} else {
    flashMessage("Failed to update profile: " . mysqli_error($conn), "error");
    header("Location: edit_profile.php");
    exit();
}