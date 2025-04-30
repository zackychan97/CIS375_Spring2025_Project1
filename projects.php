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

<div class="container mt-5">
    <h2 class="text-center mb-4">Discover Research Projects</h2>
  
    <!-- Search and Filters -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10">
            <form method="GET" action="projects.php">

                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-lg" name="search" placeholder="Search projects by title, description, or faculty..." aria-label="Search">
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select class="form-control" id="department" name="department">
                            <option value="">All Departments</option>
                            <option value="College of Business & Information Systems">College of Business & Information Systems</option>
                            <option value="The Beacom College of Computer & Cyber Sciences">The Beacom College of Computer & Cyber Sciences</option>
                            <option value="College of Arts & Sciences">College of Arts & Sciences</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select class="form-control" id="faculty" name="faculty">
                            <option value="">All Faculty</option>
                            <option value="Dr. Mohammad Tafiqur Rahman">Dr. Mohammad Tafiqur Rahman</option>
                            <option value="Michael Ham">Michael Ham</option>
                            <option value="Gillian Morris">Gillian Morris</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <select class="form-control" id="project_type" name="project_type">
                            <option value="">All Project Types</option>
                            <option value="Technology">Technology</option>
                            <option value="Health">Health</option>
                            <option value="Business">Business</option>
                        </select>
                    </div>
                </div>
                
                <div class="text-center">
                    <button class="btn btn-secondary px-4 py-2" type="submit">Search Projects</button>
                </div>
            </form>
        </div>
    </div>
  
    <!-- Project Listings  -->
    <div class="row">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($project = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card glass h-100 project-card">
                        <div class="card-content">
                            <h4 class="card-title mb-3"><?= htmlspecialchars($project['title']) ?></h4>
                            <p class="card-text mb-3"><?= substr(htmlspecialchars($project['description']), 0, 100) . (strlen($project['description']) > 100 ? '...' : '') ?></p>
                            <div class="d-flex justify-content-between mb-3">
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
    <div class="pagination-container mt-4">
        <ul class="pagination">
            <li class="pagination-item">
                <a href="#" class="pagination-link pagination-prev">Previous</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="pagination-link active">1</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="pagination-link">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="pagination-link">3</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="pagination-link">4</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="pagination-link pagination-next">Next</a>
            </li>
        </ul>
    </div>
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
