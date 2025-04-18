<?php
session_start();
require_once 'includes/db.php';

// ADMIN CHECK - SWITCH THIS TO INCLUDES
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// CAPTURE FORM DATA
$name     = trim($_POST['fullname'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

// VALIDATE ALL FIELDS ARE FILLED
if (empty($name) || empty($email) || empty($password) || empty($role)) {
    echo "All fields are required.";
    exit();
}

//VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit();
}

//VALIDATE PASSWORD LENGTH
if (strlen($password) < 6) {
    echo "Password must be at least 6 characters.";
    exit();
}

//HASH PASSWORD
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


//ADD TO DATABASE
$query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashedPassword, $role);

if (mysqli_stmt_execute($stmt)) {
    header("Location: manage_users.php");
    exit();
} else {
    echo "Failed to create user: " . mysqli_error($conn);
    exit();
}