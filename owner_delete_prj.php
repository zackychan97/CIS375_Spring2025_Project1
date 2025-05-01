<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/project_functions.php';

//GRAB USER ID AND PROJECT ID -- CAST PROJECT ID TO INT
$user_id = $_SESSION['user_id'] ?? null;
$project_id = intval(
    $_POST['project_id'] 
    ?? $_GET['id'] 
    ?? 0
);

$ownerQuery = "SELECT user_id FROM project_members WHERE role = 'owner' AND project_id = ?";
$ownerStmt = mysqli_prepare($conn, $ownerQuery);
mysqli_stmt_bind_param($ownerStmt, "i", $project_id);
mysqli_stmt_execute($ownerStmt);
$ownerResult = mysqli_stmt_get_result($ownerStmt);
$owner = mysqli_fetch_assoc($ownerResult)['user_id'] ?? null;

//COMPARE USER ID TO PROJECT OWNER ID
if ($owner !== $user_id) {
    flashMessage("You are not the owner of this project.", "error");
    header("Location: project.php?id=$project_id");
    exit();
}

// DELETE PROJECT FROM DATABASE
$deleteQuery = "DELETE FROM projects WHERE id = ? AND faculty_mentor_id = ? LIMIT 1";
$deleteStmt = mysqli_prepare($conn, $deleteQuery);
mysqli_stmt_bind_param($deleteStmt, "ii", $project_id, $user_id);
mysqli_stmt_execute($deleteStmt);
mysqli_stmt_close($deleteStmt);

flashMessage("Project deleted successfully.", "success");
header("Location: dashboard.php");