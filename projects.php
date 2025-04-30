<?php include "includes/header.php"; 
require_once 'includes/db.php';


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
";
$result = mysqli_query($conn, $query);
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

<?php include "includes/footer.php"; ?>
