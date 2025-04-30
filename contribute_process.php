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
    flashMessage("Invalid contribution.", "error");
    header("Location: contribute.php?project_id=" . urlencode($project_id));
    exit();
}

$query = "INSERT INTO contributions (project_id, user_id, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $project_id, $user_id, $content);

if ($stmt->execute()) {
    flashMessage("Contribution submitted successfully!", "success");
} else {
    flashMessage("Error submitting contribution: " . $stmt->error, "error");
}

header("Location: project.php?id=" . urlencode($project_id));
exit();
