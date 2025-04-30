<?php 
include "includes/header.php"; 
require_once 'includes/db.php';

 //Query three random projects
 $featuredQuery = "SELECT id, title, description FROM projects ORDER BY RAND() LIMIT 3";
 $featuredResult = mysqli_query($conn, $featuredQuery);
 $featuredProjects = mysqli_fetch_all($featuredResult, MYSQLI_ASSOC);
?>


<div class="container mt-4">
    <!-- Hero -->
    <div class="hero glass py-4 px-4">
        <h1 class="display-4">Collaborate and Innovate Together</h1>
        <p class="lead">Welcome to CollaboraNation – a platform where faculty and students unite to work on innovative research projects.</p>
        <div class="hero-buttons">
            <a class="btn btn-secondary" href="projects.php" role="button">Explore Projects</a>
            <a class="btn btn-outline" href="login.php" role="button">Join the Community</a>
        </div>
    </div>
  
        <!-- How It Works -->
        <section class="mt-4 mb-4">
        <h2 class="text-center">How It Works</h2>
        <div class="how-it-works-container glass py-2 px-4">
            <div class="how-it-works-steps">
                <div class="step">
                    <h3>Create an Account</h3>
                    <p>Register and get immediate access to our collaborative research platform.</p>
                </div>
                
                <div class="step">
                    <h3>Browse Projects</h3>
                    <p>Find existing research opportunities or create your own project proposal.</p>
                </div>
                
                <div class="step">
                    <h3>Collaborate</h3>
                    <p>Connect with faculty and students to bring innovative research ideas to life.</p>
                </div>
            </div>
        </div>
    </section>

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
