<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/project_functions.php';

requireAdmin();

//CAPTURE USER ID FROM URL
$userId = $_GET['id'] ?? null;

//PREVENTS SELF-DELETION
if ($userId == $_SESSION['user_id']) {
    flashMessage("You cannot delete your own account.", "error");
    header("Location: manage_users.php");
    exit();
}

//CHECK IF USER ID IS VALID AND NUMERIC
if (!$userId || !is_numeric($userId)) {
    flashMessage("Invalid user ID.", "error");
    header("Location: manage_users.php");
    exit();
}


//DELETE USER FROM DATABASE BASED ON ID FROM URL
$query = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);


//REDIRECT AFTER SUCCESSFUL DELETION
if (mysqli_stmt_execute($stmt)) {
    flashMessage("User deleted successfully.", "success");
    header("Location: manage_users.php");
    exit();
} else {
    flashMessage("Failed to delete user: " . mysqli_error($conn), "error");
    exit();
}