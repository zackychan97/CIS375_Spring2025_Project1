<?php

//SESSION START AND DB CONNECTION
session_start();
require_once 'includes/db.php';

//CAPTURE FORM DATA
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');



//VALIDATE FORM DATA
// ENSURE ALL FIELDS ARE FILLED
if (empty($email) || empty($password)) {
    echo "All fields are required.";
    exit();
}

// VALIDATE EMAIL FORMAT
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit();
}

//CHECK FOR USER IN DATABASE
$query = "SELECT id, name, password, role, title FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 1) {
    mysqli_stmt_bind_result($stmt, $userId, $name, $hashedPassword, $role, $title);
    mysqli_stmt_fetch($stmt);

    //CHECK PASSWORD
    if (password_verify($password, $hashedPassword)) {
        //SET SESSION VARIABLES
        $_SESSION['user_id'] = $userId;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        $_SESSION['title'] = $title;
        $_SESSION['name'] = $name;
        
        header("Location: dashboard.php"); 
        exit();
    } else {
        echo "Invalid login credentials.";
        exit();
    }
} else {
    echo "Invalid login credentials.";
    exit();
}

