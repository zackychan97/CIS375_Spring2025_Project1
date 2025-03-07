<?php include 'header.php'; ?>
<?php session_start(); ?>

<div class="container mt-4">
    <!-- Project Header -->
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">Project Title</h2>
            <p class="card-text">Faculty: Dr. John Doe | Department: Computer Science | Timeline: Jan 2025 - Dec 2025</p>
        </div>
    </div>

    <!-- Project Details -->
    <div class="mb-4">
        <h3>Project Description</h3>
        <p>Full description of the project including objectives, requirements, and expected outcomes goes here...</p>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4">
        <a href="#" class="btn btn-primary">Register for Project</a>
        <a href="#" class="btn btn-secondary">Share Project</a>
        <a href="#" class="btn btn-info">Download Resources</a>
        <a href="#" class="btn btn-secondary">Upload Resources for Review</a>
    </div>

    <!-- Discussion/Comments Section -->
    <div class="mb-4">
        <h3>Discussion Forum</h3>
        
        <!-- Comment Form -->
        <form method="POST" action="">
            <div class="form-group">
                <textarea class="form-control" name="comment" rows="3" placeholder="Write your comment here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>

        <!-- Store comments using sessions -->
        <?php
        $project_title = "Project Title"; // Change this dynamically if needed

        if (!isset($_SESSION['comments'][$project_title])) {
            $_SESSION['comments'][$project_title] = [];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["comment"])) {
            $new_comment = htmlspecialchars($_POST["comment"]); // Prevent XSS
            array_push($_SESSION['comments'][$project_title], $new_comment);
        }
        ?>

        <!-- Display Comments -->
        <div class="card mt-3">
            <div class="card-body">
                <?php
                if (!empty($_SESSION['comments'][$project_title])) {
                    foreach ($_SESSION['comments'][$project_title] as $comment) {
                        echo "<div class='alert alert-secondary' role='alert'>" . $comment . "</div>";
                    }
                } else {
                    echo "<p>No comments yet. Be the first to comment!</p>";
                }
                ?>
          
