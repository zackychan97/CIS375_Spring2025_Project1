<?php
require_once 'includes/db.php';

// CAPTURE FORM DATA
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// VERIFY NO EMPTY FIELDS
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo "All fields are required.";
    exit();
}

// VERIFY EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit();
}

// INSERT DATA INTO DATABASE
$query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

if (mysqli_stmt_execute($stmt)) {
    header("Location: contact.php");
    exit();
} else {
    echo "Failed to send message. " . mysqli_error($conn);
    exit();
}