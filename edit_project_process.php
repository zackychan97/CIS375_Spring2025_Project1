<?php 
session_start();
include "includes/db.php"; 
require_once 'includes/auth.php'; 
require_once 'includes/project_functions.php';



// CAPTURE FORM DATA
$project_id = $_POST['project_id'] ?? null;
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$college = $_POST['college'] ?? '';
$faculty_email = $_POST['faculty_email'] ?? '';

requireAdminOrOwner($conn, $project_id);

// CHECK IF THE PROJECT ID IS VALID
if ($project_id !== null) {
    // VALIDATE FORM IS NOT EMPTY
    if (empty($title) || empty($description) || empty($college) || empty($faculty_email)) {
        echo "All fields are required.";
    } else {
        // GRAB USER BY LOOKING FOR THE EMAIL IN THE USERS TABLE
        $userQuery = "SELECT id FROM users WHERE email = ?";
        $userStmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($userStmt, "s", $faculty_email);
        mysqli_stmt_execute($userStmt);
        $userResult = mysqli_stmt_get_result($userStmt);
        // STORE RESULT IN AN ARRAY
        $faculty_user = mysqli_fetch_assoc($userResult);

        if ($faculty_user) {
            //STORE THE FACULTY ID
            $faculty_id = $faculty_user['id'];
            //UPDATE THE PROJECT MEMBERS TABLE TO CHANGE THE OWNER
            $teamQuery = "UPDATE project_members SET user_id = ? WHERE project_id = ? AND role = 'owner'";
            $teamStmt = mysqli_prepare($conn, $teamQuery);
            mysqli_stmt_bind_param($teamStmt, "ii", $faculty_id, $project_id);
            mysqli_stmt_execute($teamStmt);
           

            // UPDATE THE PROJECT TABLE WITH THE NEW DETAILS
            $updateQuery = "UPDATE projects SET title = ?, description = ?, college = ?, faculty_mentor_id = ? WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "sssii", $title, $description, $college, $faculty_id, $project_id);
            $success = mysqli_stmt_execute($updateStmt);

            if ($success) {
                flashMessage("Project updated successfully!", "success");
                
                // REDIRECT TO THE PROJECT PAGE
                header("Location: project.php?id=" . $project_id); 
                exit();
            } else {
                flashMessage("Error updating project. Please try again.", "error");
                // REDIRECT BACK TO THE EDIT PAGE
                header("Location: edit_project.php?id=" . $project_id);
            }
        } else {
            flashMessage("Error: Faculty email does not exist.", "error");
            // REDIRECT BACK TO THE EDIT PAGE
            header("Location: edit_project.php?id=" . $project_id);
            exit();
        }
    }
} else {
    flashMessage("Error: Invalid project ID.", "error");
    // REDIRECT BACK TO THE EDIT PAGE
    header("Location: edit_project.php?id=" . $project_id);
    exit();
}

