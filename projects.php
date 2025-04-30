<?php 
include "includes/header.php"; 
require_once 'includes/db.php';

// Capture search filters
$search = $_GET['search'] ?? '';
$department = $_GET['department'] ?? '';
$faculty = $_GET['faculty'] ?? '';

// Pagination setup
$projectsPerPage = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $projectsPerPage;

// Dynamic dropdown values
$collegeResult = mysqli_query($conn, "SELECT DISTINCT college FROM projects ORDER BY college");
$facultyResult = mysqli_query($conn, "
    SELECT DISTINCT users.name 
    FROM users 
    JOIN projects ON projects.faculty_mentor_id = users.id 
    ORDER BY users.name
");

// Base query
$baseQuery = "
    FROM projects 
    JOIN users ON projects.faculty_mentor_id = users.id
    WHERE 1=1
";

$params = [];
$types = '';
$conditions = '';

// Add filters to query
if (!empty($search)) {
    $conditions .= " AND (projects.title LIKE ? OR projects.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}
if (!empty($department)) {
    $conditions .= " AND projects.college = ?";
    $params[] = $department;
    $types .= 's';
}
if (!empty($faculty)) {
    $conditions .= " AND users.name = ?";
    $params[] = $faculty;
    $types .= 's';
}

// Count total matching projects
$countStmt = mysqli_prepare($conn, "SELECT COUNT(*) $baseQuery $conditions");
if (!empty($params)) {
    mysqli_stmt_bind_param($countStmt, $types, ...$params);
}
mysqli_stmt_execute($countStmt);
mysqli_stmt_bind_result($countStmt, $totalProjects);
mysqli_stmt_fetch($countStmt);
mysqli_stmt_close($countStmt);

$totalPages = ceil($totalProjects / $projectsPerPage);

// Final query with LIMIT and OFFSET
$query = "
    SELECT 
        projects.id, 
        projects.title,
        projects.description,
        projects.college, 
        users.name AS mentor_name, 
        users.title AS mentor_title 
    $baseQuery 
    $conditions 
    LIMIT ? OFFSET ?
";

$params[] = $projectsPerPage;
$params[] = $offset;
$types .= 'ii';

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-4">
    <h2>Project Listings</h2>

    <!-- Search & Filter Form -->
    <form class="form-inline mb-4" method="GET" action="projects.php">
        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search Projects..." value="<?= htmlspecialchars($search) ?>">

        <select class="form-control mr-sm-2" name="department">
            <option value="">Department</option>
            <?php while ($row = mysqli_fetch_assoc($collegeResult)): ?>
                <option value="<?= htmlspecialchars($row['college']) ?>" <?= ($department === $row['college']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['college']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select class="form-control mr-sm-2" name="faculty">
            <option value="">Faculty</option>
            <?php while ($row = mysqli_fetch_assoc($facultyResult)): ?>
                <option value="<?= htmlspecialchars($row['name']) ?>" <?= ($faculty === $row['name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button class="btn btn-outline-success my-2" type="submit">Search</button>
    </form>

    <!-- Project Listings -->
    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($project = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="assets/placeholder.jpg" class="card-img-top" alt="Project Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($project['description'], 0, 150)) ?>...</p>
                            <p><strong>Faculty:</strong> <?= htmlspecialchars($project['mentor_name']) ?></p>
                            <p><strong>Department:</strong> <?= htmlspecialchars($project['college']) ?></p>
                            <a href="project.php?id=<?= $project['id'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-muted">No projects match your search criteria.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Project pagination">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="<?= buildPageUrl($page - 1) ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= buildPageUrl($i) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="<?= buildPageUrl($page + 1) ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php
// Helper to preserve filter parameters during pagination
function buildPageUrl($page) {
    $query = $_GET;
    $query['page'] = $page;
    return '?' . http_build_query($query);
}
?>

<?php include "includes/footer.php"; ?>
