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

	<div class="container mt-4">
		<h2>Project Listings</h2>
  
		<!-- Search & Filter Form -->
		<form class="form-inline mb-4" method="GET" action="projects.php">
			<input class="form-control mr-sm-2" type="search" name="search" placeholder="Search Projects..." aria-label="Search">
			<select class="form-control mr-sm-2" name="department">
				<option value="">Department</option>
				<option value="">College of Business & Information Systems</option>
				<option value="">The Beacom College of Computer & Cyber Sciences</option>
				<option value="">College of Arts & Sciences</option>
				<!-- Add department options -->
			</select>
			<select class="form-control mr-sm-2" name="faculty">
				<option value="">Faculty</option>
				<!-- Add faculty options -->
				<option value="">Dr. Mohammad Tafiqur Rahman</option>
				<option value="">Michael Ham</option>
				<option value="">Gillian Morris</option>
			</select>
			<select class="form-control mr-sm-2" name="project_type">
				<option value="">Project Type</option>
				<!-- Add project type options -->
				<option value="">Technology</option>
				<option value="">Health</option>
				<option value="">Business</option>
			</select>
			<button class="btn btn-outline-success my-2 " type="submit">Search</button>
		</form>
  
  <!-- Project Listings (example cards) -->
  <div class="row">
        <?php
        while ($project = mysqli_fetch_assoc($result)) {
            echo "<div class='col-md-4'>";
            echo "<div class='card mb-4 shadow-sm'>";
            echo "<img src='assets/placeholder.jpg' class='card-img-top' alt='Project Image'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($project['title']) . "</h5>";
            echo "<p class='card-text'>" . substr(htmlspecialchars($project['description']), 0, 150) . "..." . "</p>";
            echo "<p><strong>Faculty:</strong> " . htmlspecialchars($project['mentor_name']) . "</p>";
            echo "<p><strong>Department:</strong> " . htmlspecialchars($project['college']) . "</p>";
            echo "<a href='project.php?id=" . $project['id'] . "' class='btn btn-primary'>View Details</a>";
            echo "</div></div></div>";
        }
        ?>
    </div>
  
		<!-- Pagination (static example) -->
		<nav aria-label="Project pagination">
			<ul class="pagination">
				<li class="page-item"><a class="page-link" href="#">Previous</a></li>
				<li class="page-item"><a class="page-link" href="#">1</a></li>
				<li class="page-item"><a class="page-link" href="#">2</a></li>
				<li class="page-item"><a class="page-link" href="#">Next</a></li>
			</ul>
		</nav>
	</div>

<?php include "includes/footer.php"; ?>
