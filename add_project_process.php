<?php 


session_start(); 
include "includes/db.php"; 
require_once 'includes/auth.php'; 


require_once 'includes/project_functions.php';
// include 'includes/project_functions.php';
requireLogin();

// CAPTURE USER ID FROM SESSION
$user_id = $_SESSION['user_id'] ?? null;

// CHECK IF FORM IS SUBMITTED VIA POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CAPTURE FORM DATA
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $college = $_POST['college'] ?? '';
    $faculty_email = $_POST['faculty_email'] ?? '';

    // CHECK IF ALL FIELDS ARE FILLED
    if (empty($title) || empty($description) || empty($college) || empty($faculty_email)) {
        flashMessage('All fields are required.', 'error');

    } else {
        // QUERY EMAIL TO FIND FACULTY ID
        $userQuery = "SELECT id FROM users WHERE email = ?";
        $userStmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($userStmt, "s", $faculty_email);
        mysqli_stmt_execute($userStmt);
        $userResult = mysqli_stmt_get_result($userStmt);
        $faculty_user = mysqli_fetch_assoc($userResult);

        // CHECK IF FACULTY EMAIL EXISTS, ASSIGN ID OR USE LOGGED-IN USER
        if ($faculty_user) {
            $faculty_id = $faculty_user['id'];
        } else {
            
            $faculty_id = $user_id; 
        }

        // INSERT PROJECT INTO DATABASE
        $query = "INSERT INTO projects (title, description, college, faculty_mentor_id) 
                  VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $college, $faculty_id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // GET THE LAST INSERTED PROJECT ID
            $project_id = mysqli_insert_id($conn);

         
if (
    isset($_FILES['thumbnail']) && is_uploaded_file($_FILES['thumbnail']['tmp_name'])
) {

    $ext   = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
    $fname = 'thumb.' . $ext;

    $uploadDir = __DIR__ . '/public/uploads/projects/' . $project_id;
    if (! is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $target = $uploadDir . '/' . $fname;
    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target)) {
        $relPath = "projects/{$project_id}/{$fname}";
        $updStmt = mysqli_prepare(
          $conn,
          "UPDATE projects
             SET thumbnail = ?
           WHERE id = ?"
        );
        mysqli_stmt_bind_param($updStmt, 'si', $relPath, $project_id);
        mysqli_stmt_execute($updStmt);
        mysqli_stmt_close($updStmt);

    } else {
        flashMessage('Thumbnail upload failed.', 'warning');
    }
}



            // INSERT THE FACULTY MEMBER AS THE OWNER OF THE PROJECT
            $memberQuery = "INSERT INTO project_members (project_id, user_id, role) 
                            VALUES (?, ?, 'owner')";
            $memberStmt = mysqli_prepare($conn, $memberQuery);
            mysqli_stmt_bind_param($memberStmt, "ii", $project_id, $faculty_id);
            mysqli_stmt_execute($memberStmt);

            
            flashMessage("Project successfully added!");
            header("Location: project.php?id=" . $project_id); 
            exit();
        } else {
            flashMessage("Error adding project. Please try again.");
            
        }
    }
}
?>