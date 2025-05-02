<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//PUTTING FLASH MESSAGE IN HEADER MADE THINGS GET WEIRD, HENCE THE FUNCTION EXISTS CHECK

if (function_exists('flashMessage')) {
    // Do nothing, function already exists
} else {
    function flashMessage($message, $type = 'success')
    {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}

if (function_exists('displayFlash')) {
    // Do nothing, function already exists
} else {
    function displayFlash()
    {
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

            echo "<div class='container mt-3'><div class='$alertClass' style='padding: 15px; border-radius: 10px;'>" . $_SESSION['flash_message'] . "</div></div>";
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
        }
    }
}


if (function_exists('getProjectDetails')) {
    // Do nothing, function already exists
} else {
    // FUNCTION TO GET PROJECT DETAILS
    function getProjectDetails($conn, $project_id)
    {
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

if (function_exists('getProjectThumbnail')) {
    // Do nothing, function already exists
} else {
    function getProjectThumbnail(int $projectId): string
    {

        $dir = __DIR__ . "/../public/uploads/projects/{$projectId}";
        // Web path you stored in DB
        global $conn;
        $stmt = mysqli_prepare($conn, "
          SELECT thumbnail FROM projects WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $projectId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $thumb);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $baseApp = '/CIS375_Spring2025_Project1/public';
        if (! empty($thumb) && file_exists(__DIR__ . "/../public/uploads/{$thumb}")) {
            // Adjust this path to where Apache actually serves:
            return "/CIS375_Spring2025_Project1/public/uploads/{$thumb}";
        }
        // make sure this is correct and not just "/"
        return "/CIS375_Spring2025_Project1/assets/placeholder.jpg";
    }
}