<?php
session_start();
require_once 'includes/db.php';

//ADMIN CHECK - SWITCH THIS TO INCLUDES
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

//CAPTURE USER ID FROM URL
$userId = $_GET['id'] ?? null;


//DELETE USER FROM DATABASE BASED ON ID FROM URL
$query = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);


//REDIRECT AFTER SUCCESSFUL DELETION
if (mysqli_stmt_execute($stmt)) {
    header("Location: manage_users.php");
    exit();
} else {
    echo "Failed to delete user: " . mysqli_error($conn);
    exit();
}