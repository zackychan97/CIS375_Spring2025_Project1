<?php
include "includes/header.php";
include "includes/auth.php";
include "includes/project_functions.php";
include "includes/comments.php"; 
requireLogin();
require_once 'includes/db.php';



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
    <div class="project-container">
        <!-- Project Header -->
        <div class="project-header glass">
            <div class="project-header-content">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 style="margin-bottom: 0;"><?= htmlspecialchars($project['title']) ?></h2>

                    <?php if ($isLoggedIn): ?>
                        <?php if ($isRegistered && ! $isOwner): ?>
                            <!-- Only non-owner members can leave -->
                            <form method="POST">
                                <input
                                    type="hidden"
                                    name="project_id"
                                    value="<?= $projectId ?>">
                                <button
                                    type="submit"
                                    name="leave_project"
                                    class="btn btn-secondary">
                                    Leave Project
                                </button>
                            </form>
                        <?php elseif (!$isRegistered): ?>

                            <form method="POST">
                                <input type="hidden" name="project_id" value="<?= $project_id ?>">
                                <button type="submit" name="join_project" class="btn btn-secondary">Join Project</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="project-meta mt-3">
                    <span><strong>Faculty:</strong> <?= htmlspecialchars(trim($project['mentor_title'] . " " . $project['mentor_name'])) ?></span>
                    <span><strong>College:</strong> <?= htmlspecialchars($project['college']) ?></span>
                    <span><strong>Timeline:</strong> Jan 2025 - Dec 2025</span>
                </div>
            </div>
        </div>

        <!-- Project Content -->
        <div class="project-content">
            <!-- Left Side - Project Details -->
            <div class="project-details glass">
                <h3 class="section-title">Project Description</h3>
                <p><?= htmlspecialchars($project['description']) ?></p>

                <?php if ($isLoggedIn && $isOwner): ?>
                    <div class="project-actions mt-4">
                    
                    <form method="POST" action="edit_project.php?id=<?= $project_id ?>" class="d-inline ms-2" >
                            <button type="submit" name="update_project" class="btn btn-outline">Update Project</button>
                        </form>
                        <form method="POST" action="owner_delete_prj.php?id=<?= $project['id'] ?>" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            <button type="submit" name="delete_project" class="btn btn-outline">Delete Project</button>
                        </form>
                    </div>
                <?php endif; ?>

                
            </div>

            <!-- Right Side - Team and Resources -->
            <div class="project-sidebar">
                <!-- Team Members -->
                <div class="project-team glass">
                    <h3 class="section-title">Project Team</h3>
                    <div class="team-members">
                        <?php while ($member = mysqli_fetch_assoc($teamResult)): ?>
                            <div class="team-member">
                                <div class="team-member-info">
                                    <h4><?= htmlspecialchars($member['title'] . ' ' . $member['name']) ?></h4>
                                    <p class="member-role">
                                        <?php if ($member['role'] === 'owner'): ?>
                                            Faculty Lead
                                        <?php else: ?>
                                            <?= ucfirst(htmlspecialchars($member['role'])) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Resources -->
                <?php if ($isRegistered || $isOwner): ?>
                    <div class="project-resources glass">
                        <h3 class="section-title">Resources</h3>
                        <div class="resources-actions">
                            <a href="download.php" class="btn btn-secondary btn-sm mb-2">Download Resources</a>
                            <a href="upload.php" class="btn btn-secondary btn-sm">Upload Resources</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="glass ">
            <div style="padding: 30px;">
                <?php commentsSection($conn, $project_id); ?>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>