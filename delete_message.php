<?php
session_start();
require_once 'includes/db.php';

//ADMIN CHECK - SWITCH THIS TO INCLUDES
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

//CAPTURE USER ID FROM URL
$messageId = $_GET['id'] ?? null;

//DELETE CONTACT FROM DATABASE BASED ON ID FROM URL
$query = "DELETE FROM contact_messages WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $messageId);

if (mysqli_stmt_execute($stmt)) {
    header("Location: manage_messages.php");
    exit();
} else {
    echo "Failed to delete message: " . mysqli_error($conn);
    exit();
}