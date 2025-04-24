<?php include "includes/header.php"; ?>
	<div class="container mt-4">
		
		<!-- Hero Section -->
		<div class="jumbotron">
			<h1 class="display-4">Collaborate and Innovate Together</h1>
			<p class="lead">Welcome to CollaboraNation – a platform where faculty and students unite to work on innovative research projects.</p>
	
			<hr class="my-4">
			<a class="btn btn-primary btn-lg" href="projects.php" role="button">Explore Projects</a>
			<a class="btn btn-success btn-lg" href="login.php" role="button">Join the Community</a>
		</div>
  
			<!-- Featured Projects (static example) -->
		<h2>Featured Projects</h2>
		
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
			
			<!-- Additional project cards can be added here -->
		
			<div class="col-md-4">
				<div class="card mb-4 shadow-sm">
					<img src="placeholder.jpg" class="card-img-top" alt="Project Image">
					<div class="card-body">
						<h5 class="card-title">Project 2</h5>
						<p class="card-text">This is a description of project 2</p>
						<a href="project.php?id=2" class="btn btn-primary">View Details</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include "includes/footer.php"; ?>
