<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'] ?? null;

// CAPTURE FORM DATA
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// VALIDATE FIELDS ARE NOT EMPTY
if (empty($name) || empty($email)) {
    echo "Name and email are required.";
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit();
}

// CHECK FOR PASSWORD CHANGE
if (!empty($new_password) || !empty($confirm_password)) {
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }
    if (strlen($new_password) < 6) {
        echo "Password must be at least 6 characters.";
        exit();
    }

    //UPDATE WITH PASSWORD CHANGE
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
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    header("Location: dashboard.php");
    exit();
} else {
    echo "Update failed: " . mysqli_error($conn);
    exit();
}

