<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//PUTTING FLASH MESSAGE IN HEADER MADE THINGS GET WEIRD, HENCE THE FUNCTION EXISTS CHECK

if(function_exists('flashMessage')) {
    // Do nothing, function already exists
} else {
    function flashMessage($message) {
        $_SESSION['flash_message'] = $message;
    }
}

if(function_exists('displayFlash')) {
    // Do nothing, function already exists
} else {
    function displayFlash() {
        if (isset($_SESSION['flash_message'])) {
            echo "<div class='alert alert-success'>".$_SESSION['flash_message']."</div>";
            unset($_SESSION['flash_message']);
        }
    }
}


if(function_exists('getProjectDetails')) {
    // Do nothing, function already exists
} else {
    // FUNCTION TO GET PROJECT DETAILS
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
}
