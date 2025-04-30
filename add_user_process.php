<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/project_functions.php';

requireAdmin();


// CAPTURE FORM DATA
$title    = $_POST['title'] ?? '';
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

// VALIDATE ALL FIELDS ARE FILLED
if (empty($name) || empty($email) || empty($password) || empty($role)) {
    flashMessage("All fields are required.", "error");
    exit();
}

//VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flashMessage("Invalid email format.", "error");
    exit();
    
}

//VALIDATE PASSWORD LENGTH
if (strlen($password) < 6) {
    flashMessage("Password must be at least 6 characters.", "error");
    exit();
}

//HASH PASSWORD
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


//ADD TO DATABASE
$query = "INSERT INTO users (title, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssss", $title, $name, $email, $hashedPassword, $role);

if (mysqli_stmt_execute($stmt)) {
    flashMessage("User successfully created!", "success");
    header("Location: manage_users.php");
    exit();
} else {
    flashMessage("Failed to create user: " . mysqli_error($conn), "error");
    exit();
}