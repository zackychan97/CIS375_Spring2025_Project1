<?php 


function getProjectDetails($conn, $project_id) {
    // SQL QUERY TO GET PROJECT DETAILS
    $query = "
        SELECT 
            projects.id, 
            projects.title, 
            projects.description,
            projects.college, 
            users.name AS mentor_name, 
            users.title AS mentor_title 
        FROM projects 
        JOIN users ON projects.faculty_mentor_id = users.id
        WHERE projects.id = ?
    ";

    // PREPARE AND EXECUTE THE STATEMENT
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $project_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // RETURN THE PROJECT DETAILS
    return mysqli_fetch_assoc($result);
}