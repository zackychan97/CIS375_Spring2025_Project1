<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireAdmin();

//CAPTURE USER ID FROM URL
$projectId = $_GET['id'] ?? null;


//CHECK IF USER ID IS VALID AND NUMERIC
if (!$projectId || !is_numeric($projectId)) {
    echo "Invalid project ID.";
    exit();
}


//DELETE USER FROM DATABASE BASED ON ID FROM URL
$query = "DELETE FROM projects WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $projectId);


//REDIRECT AFTER SUCCESSFUL DELETION
if (mysqli_stmt_execute($stmt)) {
    header("Location: manage_projects.php");
    exit();
} else {
    echo "Failed to delete project: " . mysqli_error($conn);
    exit();
}