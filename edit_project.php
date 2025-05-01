<?php 
include "includes/header.php"; 
require_once 'includes/db.php'; 
require_once 'includes/auth.php'; 
// requireAdmin(); 

// CAPTURE PROJECT ID FROM URL
$project_id = $_GET['id'] ?? null;
requireAdminOrOwner($conn, $project_id);

// QUERY TO FETCH PROJECT DETAILS BASED ON ID FROM URL
$query = "SELECT title, description, college, faculty_mentor_id FROM projects WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $project_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$project = mysqli_fetch_assoc($result);

// REDIRECT IF PROJECT NOT FOUND
if (!$project) {
    header("Location: projects.php");
    exit();
}

// FETCH FACULTY EMAIL BASED ON FACULTY MENTOR ID
$faculty_email_query = "SELECT email FROM users WHERE id = ?";
$faculty_email_stmt = mysqli_prepare($conn, $faculty_email_query);
mysqli_stmt_bind_param($faculty_email_stmt, "i", $project['faculty_mentor_id']);
mysqli_stmt_execute($faculty_email_stmt);
$faculty_email_result = mysqli_stmt_get_result($faculty_email_stmt);
$faculty_email = mysqli_fetch_assoc($faculty_email_result)['email'];
?>

<div class="container mt-4">
    <h2>Edit Project</h2>

    <form action="edit_project_process.php" method="post">
        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project_id) ?>">

        <div class="form-group mb-3">
            <label for="title">Project Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?= htmlspecialchars($project['title']) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" required><?= htmlspecialchars($project['description']) ?></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="college">College</label>
            <input type="text" class="form-control" name="college" id="college" value="<?= htmlspecialchars($project['college']) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="faculty_email">Faculty Email</label>
            <input type="email" class="form-control" name="faculty_email" id="faculty_email" value="<?= htmlspecialchars($faculty_email) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
