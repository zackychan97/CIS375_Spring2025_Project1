<?php include 'header.php'; ?>
<!doctype html>
<html>
<link rel="stylesheet" href="style.css">
<div class="container mt-4">
    <!-- Project Header -->
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">Project Title</h2>
            <p class="card-text">Faculty: Dr. John Doe | Department: Computer Science | Timeline: Jan 2025 - Dec 2025
            </p>
        </div>
    </div>

    <!-- Project Details -->
    <div class="mb-4">
        <h3>Project Description</h3>
        <p>Full description of the project including objectives, requirements, and expected outcomes goes here...</p>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4">
        <a href="register_project.php?id=1" class="btn btn-primary">Register for Project</a>
        <a href="#" class="btn btn-secondary">Share Project</a>
        <a href="#" class="btn btn-info">Download Resources</a>
    </div>

    <!-- Discussion/Comments Section (placeholder) -->
    <div>
        <h3>Discussion Forum</h3>
        <div class="card">
            <div class="card-body">
                <p>No comments yet. Be the first to comment!</p>
            </div>
        </div>
    </div>
</div>

</html>
<?php include 'footer.php'; ?>