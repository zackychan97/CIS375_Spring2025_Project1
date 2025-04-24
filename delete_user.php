<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireAdmin();

//CAPTURE USER ID FROM URL
$userId = $_GET['id'] ?? null;

//PREVENTS SELF-DELETION
if ($userId == $_SESSION['user_id']) {
    echo "You cannot delete your own account.";
    exit();
}

//CHECK IF USER ID IS VALID AND NUMERIC
if (!$userId || !is_numeric($userId)) {
    echo "Invalid user ID.";
    exit();
}


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