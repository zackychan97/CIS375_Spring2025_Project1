<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//PUTTING FLASH MESSAGE IN HEADER MADE THINGS GET WEIRD, HENCE THE FUNCTION EXISTS CHECK

if(function_exists('flashMessage')) {
    // Do nothing, function already exists
} else {
    function flashMessage($message, $type = 'success') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}

if(function_exists('displayFlash')) {
    // Do nothing, function already exists
} else {
    function displayFlash() {
        if (isset($_SESSION['flash_message'])) {
            $type = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'success';
            $alertClass = 'alert glass';
            
            if ($type == 'error') {
                $alertClass .= ' bg-danger';
            } else if ($type == 'warning') {
                $alertClass .= ' bg-warning';
            } else {
                $alertClass .= ' bg-success';
            }
            
            echo "<div class='container mt-3'><div class='$alertClass' style='padding: 15px; border-radius: 10px;'>".$_SESSION['flash_message']."</div></div>";
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
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
