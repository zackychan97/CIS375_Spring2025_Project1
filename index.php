<?php 
include "includes/header.php"; 
require_once 'includes/db.php';

 //Query three random projects
 $featuredQuery = "SELECT id, title, description FROM projects ORDER BY RAND() LIMIT 3";
 $featuredResult = mysqli_query($conn, $featuredQuery);
 $featuredProjects = mysqli_fetch_all($featuredResult, MYSQLI_ASSOC);
?>

<div class="container mt-4">

    <!-- Hero Section -->
    <div class="jumbotron">
        <h1 class="display-4">Collaborate and Innovate Together</h1>
        <p class="lead">Welcome to CollaboraNation - a platform where faculty and students unite to work on innovative research projects.</p>

		<hr class="my-4">
        <a class="btn btn-primary btn-lg" href="projects.php" role="button">Explore Projects</a>
        <a class="btn btn-success btn-lg" href="login.php" role="button">Join the Community</a>
    </div>

    <!-- 3 Random Featured projects -->
    <h2>Featured Projects</h2>

    <div class="row">
        <?php if (!empty($featuredProjects)): ?>
            <?php foreach ($featuredProjects as $project): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="assets/placeholder.jpg" class="card-img-top" alt="Project Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($project['description']) ?></p>
                            <a href="project.php?id=<?= $project['id'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p class="text-muted">No featured projects available at this time.</p>
        <?php endif; ?>
    
	</div>
</div>



<?php include "includes/footer.php"; ?>
