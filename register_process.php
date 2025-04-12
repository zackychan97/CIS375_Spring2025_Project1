<?php

//SESSION START AND DB CONNECTION
session_start();
require_once 'includes/db.php';


//CAPTURE FORM DATA
$name = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

//VALIDATE FORM DATA
// ENSURE ALL FIELDS ARE FILLED
if (empty($name) || empty($email) || empty($password) || empty($role)) {
    echo "All fields are required.";
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit();
}

// VALIDATE PASSWORD LENGTH
if (strlen($password) < 6) {
    echo "Password must be at least 6 characters long.";
    exit();
}

// VALIDATE NAME LENGTH
if (strlen($name) < 2) {
    echo "Name must be at least 2 characters long.";
    exit();
}

//CHECK IF EMAIL ALREADY EXISTS - I don't know enough about stored procedures to use them yet
$query = "SELECT id FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

//IF ROWS RETURNED, EMAIL ALREADY EXISTS
if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "An account with that email already exists.";
    exit();
}

//HASH PASSWORD
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

//INSERT DATA INTO DATABASE
$insertQuery = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertQuery);
mysqli_stmt_bind_param($insertStmt, "ssss", $name, $email, $hashedPassword, $role);

//VERIFY INSERTION, IF SUCCESSFUL, REDIRECT TO LOGIN PAGE
$success = mysqli_stmt_execute($insertStmt);

if ($success) {
    header("Location: login.php");
    exit;
} else {
    echo "An error occurred during registration.";
    exit;
}
