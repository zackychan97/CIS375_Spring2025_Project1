<?php

//SESSION START AND DB CONNECTION
session_start();
require_once 'includes/db.php';
require_once 'includes/project_functions.php';


//CAPTURE FORM DATA
$title = $_POST['title'] ?? ''; 
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? '';

//VALIDATE FORM DATA
// ENSURE ALL FIELDS ARE FILLED
if (empty($name) || empty($email) || empty($password) || empty($role)) {
    flashMessage("All fields are required.", "error");
    header("Location: register.php");
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flashMessage("Invalid email format.", "error");
    header("Location: register.php");
    exit();
}

// VALIDATE PASSWORD LENGTH
if (strlen($password) < 6 || $password !== $confirmPassword) {
    flashMessage("Password must be at least 6 characters and match the confirmation.", "error");
    header("Location: register.php");
    exit();
}

// VALIDATE NAME LENGTH
if (strlen($name) < 2) {
    flashMessage("Name must be at least 2 characters long.", "error");
    header("Location: register.php");
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
    flashMessage("An account with that email already exists.", "error");
    header("Location: register.php");   
    exit();
}

//HASH PASSWORD
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

//INSERT DATA INTO DATABASE
$insertQuery = "INSERT INTO users (title, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertQuery);
mysqli_stmt_bind_param($insertStmt, "sssss", $title, $name, $email, $hashedPassword, $role);


//VERIFY INSERTION, IF SUCCESSFUL, REDIRECT TO LOGIN PAGE
$success = mysqli_stmt_execute($insertStmt);

if ($success) {
    flashMessage("Registration successful! You can now log in.", "success");
    header("Location: login.php");
    exit();
} else {    
    flashMessage("An error occurred during registration. Please try again.", "error");
    echo "An error occurred during registration.";
    exit();
}
