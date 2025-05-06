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
            <a class="btn btn-secondary px-4 py-2" href="login.php" role="button">Join the Community</a>
        </div>
    </div>

    <!-- Featured Projects -->
    <section class="mb-5 mt-5">
        <h2 class="text-center mb-3">Featured Projects</h2>
        <div class="featured-projects-container">
            <?php if (!empty($featuredProjects)): ?>
                <?php foreach ($featuredProjects as $project): ?>
                    <a href="project.php?id=<?= $project['id'] ?>" class="featured-project-card">
                        <div class="featured-project-content">
                            <h3 class="featured-project-title"><?= htmlspecialchars($project['title']) ?></h3>
                            <p class="featured-project-description">
                                <?= substr(htmlspecialchars($project['description']), 0, 200) ?>
                                <?= strlen($project['description']) > 200 ? '...' : '' ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="featured-empty-state">
                    <p>No featured projects available at this time.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="projects.php" class="btn btn-outline btn-sm">View More Projects</a>
        </div>
    </section>

<?php include "includes/footer.php"; ?>
