<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/project_functions.php';

requireAdmin();


//CAPTURE INFO FROM POST DATA
$userId = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$title = trim($_POST['title'] ?? '');

//ENSURE ALL FIELDS ARE FILLED
if (empty($name) || empty($email) || empty($role)) {
    flashMessage("All fields are required.", "error");
    header("Location: edit_user.php?id=" . $userId);
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flashMessage("Invalid email format.", "error");
    header("Location: edit_user.php?id=" . $userId);
    exit();
}

// VALIDATE PASSWORD LENGTH
if (!empty($password) && strlen($password) < 6) {
    flashMessage("Password must be at least 6 characters.", "error");
    header("Location: edit_user.php?id=" . $userId);
    exit();
}


//BUILD TWO STATEMENTS - ONE FOR PASSWORD CHANGE, ONE FOR NO PASSWORD CHANGE
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET title = ?, name = ?, email = ?, role = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $title, $name, $email, $role, $hashedPassword, $userId);
} else {
    $query = "UPDATE users SET title = ?, name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $name, $email, $role, $userId);
}

//EXECUTE THE QUERY AND REDIRECT
if (mysqli_stmt_execute($stmt)) {
    flashMessage("User updated successfully!", "success");
    header("Location: manage_users.php");
    exit();
} else {
    flashMessage("Failed to update user. Please try again.", "error");
    exit();
}