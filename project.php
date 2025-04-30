<?php
include "includes/header.php";
include "includes/auth.php";
include "includes/project_functions.php";
include "includes/comments.php"; // Include comments functionality
// requireLogin();
require_once 'includes/db.php';

 // Process comment submission

$project_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$isOwner = false;
$isLoggedIn = false;
if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}

processCommentSubmission($conn, $project_id);
// GRABS PROJECT DETAILS FROM FUNCTION
$project = getProjectDetails($conn, $project_id);

if (!$project) {
    echo "PROJECT NOT FOUND.";
    exit();
}


//QUERY TO CHECK IF USER IS ALREADY A MEMBER OF THE PROJECT
$userQuery = "SELECT * FROM project_members WHERE user_id = ? AND project_id = ?";
$userStmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($userStmt, "ii", $user_id, $project_id);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);
//CHECK IF USER IS ALREADY A MEMBER
//SETS BUTTON TO JOIN OR LEAVE PROJECT
if (mysqli_num_rows($userResult) > 0) {
    $isRegistered = true; //ALREADY A MEMBER
} else {
    $isRegistered = false; // NOT A MEMBER
}

// BUTTON FUNCTIONALITY TO JOIN OR LEAVE PROJECT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['join_project']) && !$isRegistered) {
        // ADD USER TO THE PROJECT AS A 'CONTRIBUTOR'
        $insertQuery = "INSERT INTO project_members (project_id, user_id, role) VALUES (?, ?, 'contributor')";
        $insertStmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, "ii", $project_id, $user_id);

        if (mysqli_stmt_execute($insertStmt)) {
            header("Location: project.php?id=$project_id");
        }
    }

    if (isset($_POST['leave_project']) && $isRegistered) {
        // REMOVE USER FROM PROJECT
        $deleteQuery = "DELETE FROM project_members WHERE project_id = ? AND user_id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "ii", $project_id, $user_id);

        if (mysqli_stmt_execute($deleteStmt)) {
            header("Location: project.php?id=$project_id");
        }
    }
}
//PREPARD STATEMENT TO GET PROJECT TEAM MEMBERS
$teamQuery = "SELECT users.title, users.name, users.id, project_members.role 
              FROM project_members 
              JOIN users ON project_members.user_id = users.id 
              WHERE project_members.project_id = ?
              ORDER BY FIELD(project_members.role, 'owner') DESC, users.name ASC";
$teamStmt = mysqli_prepare($conn, $teamQuery);
mysqli_stmt_bind_param($teamStmt, "i", $project_id);
mysqli_stmt_execute($teamStmt);
$teamResult = mysqli_stmt_get_result($teamStmt);


foreach ($teamResult as $member) {
        // CHECKS IF THE USER IS THE OWNER OF THE PROJECT
    if ($member['role'] == 'owner' && $member['id'] == $user_id) {
        $isOwner = true;
        break;
    }
}
// RESETS POINTER TO THE START OF THE RESULT SET
mysqli_data_seek($teamResult, 0);


?>

<div class="container mt-4">
    <!-- Project Header -->
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($project['title']) ?></h2>
            <p class="card-text">
                <strong>Faculty:</strong> <?= htmlspecialchars(trim($project['mentor_title'] . " " . $project['mentor_name'])) ?> |
                <strong>College:</strong> <?= htmlspecialchars($project['college']) ?> |
                <strong>Timeline:</strong> Jan 2025 - Dec 2025
            </p>
        </div>
    </div>

    <!-- Project Details -->
    <div class="mb-4">
        <h3>Project Description</h3>
        <p><?= htmlspecialchars($project['description']) ?></p>
    </div>

    <!-- Project Team -->
    <div class="mb-4">
  <h3>Project Team</h3>
  <ul>
    <!-- LOOPS THROUGH MEMBERS TAGGING OWNER WITH STRONG HTML -->
    <?php while ($member = mysqli_fetch_assoc($teamResult)): ?>
      <li>
        <?php if ($member['role'] === 'owner'): ?>
          <strong>Faculty Lead:</strong>
        <?php endif; ?>
        <?= htmlspecialchars($member['name']) ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

    <!-- Action Buttons -->
    <?php if ($isLoggedIn): ?>
    <div class="mb-4">
        <?php if ($isRegistered): ?>
            <form method="POST"><button type="submit" name="leave_project" class="btn btn-danger">Leave Project</button></form>
        <?php else: ?>
            <form method="POST"><button type="submit" name="join_project" class="btn btn-primary">Join Project</button></form>
        <?php endif; ?>

        <?php if ($isOwner): ?>
            <form method="POST" action="owner_delete_prj.php?id=<?php echo $project['id']; ?>" onsubmit="return confirm('Are you sure you want to delete this project?');">
                <button type="submit" name="delete_project" class="btn btn-danger">Delete Project</button>
            </form>
        <?php endif; ?>
    </div>

        <a href="#" class="btn btn-secondary">Share Project</a>
        <a href="#" class="btn btn-info">Download Resources</a>
        <a href="#" class="btn btn-secondary">Upload Resources for Review</a>
    </div>
    <?php endif; ?>

    <!-- Discussion/Comments Section -->
    <?php commentsSection($conn, $project_id); ?>

            <?php include "includes/footer.php"; ?>