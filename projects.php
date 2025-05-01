<?php
include "includes/header.php";
require_once 'includes/db.php';

// Capture filters
$search = $_GET['search'] ?? '';
$department = $_GET['department'] ?? '';
$faculty = $_GET['faculty'] ?? '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// Dynamic dropdowns
$departmentResult = mysqli_query($conn, "SELECT DISTINCT college FROM projects ORDER BY college");
$facultyResult = mysqli_query($conn, "
    SELECT DISTINCT users.name 
    FROM users 
    JOIN projects ON users.id = projects.faculty_mentor_id 
    ORDER BY users.name
");

// Base query
$conditions = "WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $conditions .= " AND (projects.title LIKE ? OR projects.description LIKE ? OR users.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'sss';
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

// Count total
$countQuery = "SELECT COUNT(*) FROM projects JOIN users ON projects.faculty_mentor_id = users.id $conditions";
$countStmt = mysqli_prepare($conn, $countQuery);
if (!empty($params)) {
    mysqli_stmt_bind_param($countStmt, $types, ...$params);
}
mysqli_stmt_execute($countStmt);
mysqli_stmt_bind_result($countStmt, $totalProjects);
mysqli_stmt_fetch($countStmt);
mysqli_stmt_close($countStmt);
$totalPages = max(1, ceil($totalProjects / $limit));

// Final paginated query
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
    $conditions
    LIMIT ? OFFSET ?
";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Discover Research Projects</h2>

    <!-- Search & Filter Form -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10">
            <form method="GET" action="projects.php">
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-lg" name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Search projects by title, description, or faculty..." aria-label="Search">
                </div>

                <div class="row mb-2">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <select class="form-control" name="department">
                            <option value="">All Departments</option>
                            <?php while ($row = mysqli_fetch_assoc($departmentResult)): ?>
                                <option value="<?= htmlspecialchars($row['college']) ?>" <?= $department === $row['college'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['college']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2 mb-md-0">
                        <select class="form-control" name="faculty">
                            <option value="">Faculty</option>
                            <?php while ($row = mysqli_fetch_assoc($facultyResult)): ?>
                                <option value="<?= htmlspecialchars($row['name']) ?>" <?= ($faculty === $row['name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <button class="btn btn-secondary px-4 py-2 " type="submit">Search Projects</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Project Listings -->
    <div class="row">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($project = mysqli_fetch_assoc($result)) {
        ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card glass h-100 project-card">
                        <div class="card-content">
                            <h4 class="card-title mb-3"><?= htmlspecialchars($project['title']) ?></h4>
                            <p class="card-text mb-3">
                                <?= substr(htmlspecialchars($project['description']), 0, 100) ?>
                                <?= strlen($project['description']) > 100 ? '...' : '' ?>
                            </p>
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Faculty:</strong> <?= htmlspecialchars($project['mentor_title'] . ' ' . $project['mentor_name']) ?></span>
                            </div>
                            <p class="mb-3"><strong>College:</strong> <?= htmlspecialchars($project['college']) ?></p>
                            <a href="project.php?id=<?= $project['id'] ?>" class="btn btn-secondary w-100">View Details</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<div class="col-12 text-center"><p>No projects found. Try adjusting your search criteria.</p></div>';
        }
        ?>
    </div>

    <!-- Pagination -->
 <!-- Pagination -->
<div class="pagination-container mt-4">
    <ul class="pagination justify-content-center">
        <!-- Previous Link -->
        <?php if ($page > 1): ?>
            <li class="pagination-item">
                <a href="<?= buildPageUrl($page - 1) ?>" class="pagination-link pagination-prev">Previous</a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="pagination-item">
                <a href="<?= buildPageUrl($i) ?>" class="pagination-link <?= ($i === $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next Link -->
        <?php if ($page < $totalPages): ?>
            <li class="pagination-item">
                <a href="<?= buildPageUrl($page + 1) ?>" class="pagination-link pagination-next">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</div>

<?php
// Preserves query parameters in pagination links
function buildPageUrl($page) {
    $query = $_GET;
    $query['page'] = $page;
    return '?' . http_build_query($query);
}
?>

<?php include "includes/footer.php"; ?>