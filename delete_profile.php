<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/project_functions.php';



//CAPTURE USER ID FROM URL
$userId = $_SESSION['user_id'] ?? null;

//ENSURES DELETION OF SELF
if ($userId !== $_SESSION['user_id']) {
    flashMessage("Error.", "error");
    header("Location: dashboard.php");
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
    header("Location: logout.php");
    exit();
} else {
    flashMessage("Failed to delete user: " . mysqli_error($conn), "error");
    exit();
}