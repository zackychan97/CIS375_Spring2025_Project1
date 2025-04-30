<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/project_functions.php';
requireLogin();

$user_id = $_SESSION['user_id'] ?? null;
$project_id = $_POST['project_id'] ?? null;
$content = trim($_POST['content'] ?? '');

if (!$user_id || !$project_id || empty($content)) {
    flashMessage("Invalid comment submission.", "error");
    header("Location: project.php?id=" . urlencode($project_id));
    exit();
}

// Insert the comment
$query = "INSERT INTO comments (project_id, user_id, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $project_id, $user_id, $content);

if ($stmt->execute()) {
    flashMessage("Comment added successfully!", "success");
} else {
    flashMessage("Error adding comment: " . $stmt->error, "error");
}

header("Location: project.php?id=" . urlencode($project_id));
exit();
