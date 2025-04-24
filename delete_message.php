<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireAdmin();

//CAPTURE USER ID FROM URL
$messageId = $_GET['id'] ?? null;


//CHECK IF MESSAGE ID IS VALID AND NUMERIC
if (!$messageId || !is_numeric($messageId)) {
    echo "Invalid message ID.";
    exit();
}

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