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
    <div class="row">
        <!-- SIDEBAR.PHP -->
        <?php include "includes/sidebar.php"; ?>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-lg p-4">
                <h2 class="text-center">Welcome, <?= htmlspecialchars($fullname) ?></h2>
                <p class="text-center">Here you can manage your projects and view your profile.</p>

                <!-- User Projects Section -->
                <h3 class="mt-4">Your Projects</h3>
                <div class="row">
                    <!-- CHECKS FOR EMPTY RESULTS FROM QUERY -->
                    <?php if (empty($projects)): ?>
                        <p>You are not part of any projects yet. Join one today!</p>
                    <?php else: ?>
                        <!-- LOOPS THROUGH RESULTS, BUILDS CARD FOR EACH PROJECT -->
                        <?php foreach ($projects as $project): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                                        <a href="project.php?id=<?= $project['id'] ?>" class="btn btn-info">View Project</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
                    <nav aria-label="Project pagination">
			<ul class="pagination">
				<li class="page-item"><a class="page-link" href="#">Previous</a></li>
				<li class="page-item"><a class="page-link" href="#">1</a></li>
				<li class="page-item"><a class="page-link" href="#">2</a></li>
				<li class="page-item"><a class="page-link" href="#">Next</a></li>
			</ul>
		</nav>
        
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
