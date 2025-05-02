<?php
include "includes/header.php";
require_once 'includes/db.php';

// CAPTURE USER ROLE FROM SESSION, OR DEFAULT TO 'guest'
$role = $_SESSION['role'] ?? 'guest';
$user_id = $_SESSION['user_id'] ?? null;
$title = $_SESSION['title'] ?? '';
$name = $_SESSION['name'] ?? '';
$fullname = trim($title . ' ' . $name);

// QUERY TO SELECT USER PROJECTS FROM PROJECT MEMBERS TABLE
$query = "
    SELECT projects.id, projects.title, projects.description, projects.college, 
           project_members.role
    FROM project_members
    JOIN projects ON project_members.project_id = projects.id
    WHERE project_members.user_id = ?
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// STORES RESULTS IN AN ARRAY
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container mt-5">
    <!-- Dashboard Main Section -->
    <div class="glass p-5 mb-4">
        <!-- Header and Action Buttons -->
        <div class="row welcome-section">
            <div class="col-md-8">

                <h2 class="mb-3">Welcome, <?= htmlspecialchars($fullname) ?></h2>
                <p class="text-muted"><?= ucfirst(htmlspecialchars($role)) ?></p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <a href="manage_users.php" class="btn btn-secondary me-2 px-2 py-1">
                        Admin: Manage Users
                    </a>
                    <a href="manage_projects.php" class="btn btn-secondary me-2 px-2 py-1">
                        Admin: Manage Projects
                    </a>
                    <a href="manage_messages.php" class="btn btn-secondary me-2 px-2 py-1">
                        Admin: Manage Messages
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="edit_profile.php" class="btn btn-secondary me-2 px-2 py-1">Edit Profile</a>
                <a href="delete_profile.php" class="btn btn-danger me-2 px-2 py-1">Delete Profile</a>
                <a href="logout.php" class="btn btn-outline px-2 py-1">Logout</a>
            </div>
        </div>

        <!-- Projects -->
        <h3 class="section-heading">My Projects</h3>


        <?php if (empty($projects)): ?>
            <div class="text-center p-5">
                <p class="mb-4">You are not part of any projects yet.</p>
                <a href="projects.php" class="btn btn-secondary px-4 py-2">Browse Available Projects</a>
            </div>
        <?php else: ?>
            <div class="row px-4">
                <?php foreach ($projects as $project): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="project.php?id=<?= $project['id'] ?>" class="dashboard-project-card">
                            <div class="card-content">
                                <h4 class="card-title mb-3"><?= htmlspecialchars($project['title']) ?></h4>
                                <p class="card-text mb-4"><?= htmlspecialchars(substr($project['description'], 0, 100)) . (strlen($project['description']) > 100 ? '...' : '') ?></p>
                                <div class="project-meta">
                                    <span><strong>Role:</strong> <?= ucfirst(htmlspecialchars($project['role'])) ?></span>
                                    <span><strong>College:</strong> <?= htmlspecialchars($project['college']) ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($role === 'admin' || $role === 'professor'): ?>
            <div class="text-center mt-4 pb-3">
                <a href="add_project.php" class="btn btn-outline mb-4 px-4 py-2">Create New Project</a>
            </div>
        <?php endif; ?>
        <?php if ($role === 'admin'): ?>

        <?php endif; ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>