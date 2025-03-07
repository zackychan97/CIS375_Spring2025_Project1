<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
	<link rel="stylesheet" href="style.css">
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
			<div class="col-md-4">
				<div class="card mb-4 shadow-sm">
					<img src="placeholder.jpg" class="card-img-top" alt="Project Image">
					<div class="card-body">
						<h5 class="card-title">Project Title</h5>
						<p class="card-text">Brief description of the project.</p>
						<a href="project.php?id=1" class="btn btn-primary">View Details</a>
					</div>
				</div>
			</div>
			<!-- Repeat project cards as needed -->
			<div class="col-md-4">
				<div class="card mb-4 shadow-sm">
					<img src="placeholder.jpg" class="card-img-top" alt="Project Image">
					<div class="card-body">
						<h5 class="card-title">Project 2</h5>
						<p class="card-text">Brief description of the project 2.</p>
						<a href="project.php?id=2" class="btn btn-primary">View Details</a>
					</div>
				</div>
			</div>
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
</html>
<?php include 'footer.php'; ?>
