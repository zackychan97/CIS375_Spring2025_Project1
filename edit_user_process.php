<?php
session_start();
require_once 'includes/db.php';

//ADMIN CHECK - SWITCH THIS TO INCLUDES
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}


//CAPTURE INFO FROM POST DATA
$userId = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

//ENSURE ALL FIELDS ARE FILLED
if (empty($name) || empty($email) || empty($role)) {
    echo "All fields are required.";
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit();
}

// VALIDATE PASSWORD LENGTH
if (!empty($password) && strlen($password) < 6) {
    echo "Password must be at least 6 characters.";
    exit();
}


//BUILD TWO STATEMENTS - ONE FOR PASSWORD CHANGE, ONE FOR NO PASSWORD CHANGE
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET name = ?, email = ?, role = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $role, $hashedPassword, $userId);
} else {
    $query = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $userId);
}

//EXECUTE THE QUERY AND REDIRECT
if (mysqli_stmt_execute($stmt)) {
    header("Location: manage_users.php");
    exit();
} else {
    echo "Failed to update user. " . mysqli_error($conn);
    exit();
}