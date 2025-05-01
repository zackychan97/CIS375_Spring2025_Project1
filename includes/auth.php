<?php

// CHECK IF USER IS LOGGED IN
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

// VERIFY USER ROLE = ADMIN
function requireAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}



function requireAdminOrOwner(mysqli $conn, int $projectId): void {
   
    requireLogin();

    $userId  = $_SESSION['user_id'];
    $isAdmin = (($_SESSION['role'] ?? '') === 'admin');

    // CHECK FOR OWNER ROLE IN THE PROJECT MEMBERS TABLE
    $ownerCheck = mysqli_prepare($conn, "SELECT 1 FROM project_members WHERE project_id = ? AND user_id = ? AND role = 'owner' LIMIT 1");
    mysqli_stmt_bind_param($ownerCheck, 'ii', $projectId, $userId);
    mysqli_stmt_execute($ownerCheck);
    mysqli_stmt_store_result($ownerCheck);
    $isOwner = mysqli_stmt_num_rows($ownerCheck) > 0;
    mysqli_stmt_close($ownerCheck);

    // DENY ACCESS IF NOT ADMIN OR OWNER
    if (! $isAdmin && ! $isOwner) {
        flashMessage('ACCESS DENIED: You must be an admin or the project owner.', 'error');
        header("Location: project.php?id={$projectId}");
        exit();
    }
}