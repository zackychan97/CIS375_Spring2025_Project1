<?php
session_start();





require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/project_functions.php'; // for flashMessage
requireLogin();

// Get logged-in user ID
$user_id = $_SESSION['user_id'] ?? null;

// Validate file upload
if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['upload_file']['tmp_name'];
    $fileName = basename($_FILES['upload_file']['name']);
    $fileType = mime_content_type($fileTmpPath);
    $fileSize = filesize($fileTmpPath);
    $fileData = file_get_contents($fileTmpPath);

    // Allowed MIME types
    $allowedExts = ['jpg','jpeg','png','pdf','txt'];
        if (!in_array($fileType, $allowedExts)) {
        flashMessage("File type not allowed. Only JPG, PNG, PDF, and TXT files are accepted.", "error");
        header("Location: upload.php");
        exit();
    }

    // Optional foreign keys
    $project_id = !empty($_POST['project_id']) ? intval($_POST['project_id']) : null;
    $contribution_id = !empty($_POST['contribution_id']) ? intval($_POST['contribution_id']) : null;

    // Prepare SQL
    $query = "INSERT INTO uploads (user_id, project_id, contribution_id, file_name, file_type, file_size, file_data)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        flashMessage("Database error: " . $conn->error, "error");
        header("Location: upload.php");
        exit();
    }

    $stmt->bind_param("iiissib", $user_id, $project_id, $contribution_id, $fileName, $fileType, $fileSize, $fileData);

    if ($stmt->execute()) {
        flashMessage("File uploaded successfully!", "success");
        header("Location: dashboard.php"); // or wherever you want to go after upload
        exit();
    } else {
        flashMessage("Upload failed: " . $stmt->error, "error");
        header("Location: upload.php");
        exit();
    }
} else {
    flashMessage("No file uploaded or an error occurred.", "error");
    header("Location: upload.php");
    exit();
}
