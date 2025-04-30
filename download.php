<!-- Anywhere in app, link to this like: <a href="download.php?id=3">Download file</a> -->
<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

// Get file ID from query string
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($file_id <= 0) {
    die("Invalid file ID.");
}

// Fetch file data
$query = "SELECT file_name, file_type, file_size, file_data FROM uploads WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $file_id);
$stmt->execute();
$result = $stmt->get_result();

if ($file = $result->fetch_assoc()) {
    // Output headers
    header("Content-Type: " . $file['file_type']);
    header("Content-Disposition: attachment; filename=\"" . $file['file_name'] . "\"");
    header("Content-Length: " . $file['file_size']);
    echo $file['file_data'];
    exit();
} else {
    echo "File not found.";
    exit();
}
