<?php 
include "includes/header.php"; 
require_once 'includes/db.php';

// Query 3 random featured projects
$featuredQuery = "SELECT id, title, description FROM projects ORDER BY RAND() LIMIT 3";
$featuredResult = mysqli_query($conn, $featuredQuery);
$featuredProjects = mysqli_fetch_all($featuredResult, MYSQLI_ASSOC);
?>

<div class="container mt-4">
    <!-- Hero -->
    <div class="hero glass py-5 px-4 mb-4 text-center">
        <h1 class="display-4">Collaborate and Innovate Together</h1>
        <p class="lead">Welcome to CollaboraNation - a platform where faculty and students unite to work on innovative research projects.</p>
        <div class="d-flex justify-content-center gap-3 mt-3">
            <a class="btn btn-secondary px-4 py-2" href="projects.php" role="button">Explore Projects</a>
            <a class="btn btn-outline px-4 py-2" href="login.php" role="button">Join the Community</a>
        </div>
    </div>

    <!-- How It Works -->
    <section class="mb-5">
        <h2 class="text-center mb-4">How It Works</h2>
        <div class="how-it-works-container glass py-4 px-4">
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <h4>Create an Account</h4>
                    <p>Register and get immediate access to our collaborative research platform.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h4>Browse Projects</h4>
                    <p>Find existing research opportunities or create your own project proposal.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h4>Collaborate</h4>
                    <p>Connect with faculty and students to bring innovative research ideas to life.</p>
                </div>
            </div>
        </div>
    </section>

<!-- Featured Projects -->
<section class="mb-5">
    <h2 class="text-center mb-3">Featured Projects</h2>
    <div class="row justify-content-center">
        <?php if (!empty($featuredProjects)): ?>
            <?php foreach ($featuredProjects as $project): ?>
                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                    <div class="card glass project-card h-100 p-2 small">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-2"><?= htmlspecialchars($project['title']) ?></h6>
                            <p class="card-text mb-2">
                                <?= substr(htmlspecialchars($project['description']), 0, 600) ?>
                                <?= strlen($project['description']) > 600 ? '...' : '' ?>
                            </p>
                            <a href="project.php?id=<?= $project['id'] ?>" class="btn btn-sm btn-secondary mt-auto">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No featured projects available at this time.</p>
            </div>
        <?php endif; ?>
    </div>
</section>


<?php include "includes/footer.php"; ?>
