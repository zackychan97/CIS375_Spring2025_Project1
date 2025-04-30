<?php include "includes/header.php"; ?>
<?php require_once "includes/auth.php"; ?>
<?php requireLogin(); ?>
<?php require_once 'includes/db.php'; ?>

<?php
$project_id = $_GET['project_id'] ?? null;
if (!$project_id) {
    echo "Invalid project ID.";
    include "includes/footer.php";
    exit();
}
?>

<div class="container mt-4">
    <h2>Add a Contribution</h2>
    <form action="contribute_process.php" method="POST">
        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project_id); ?>">

        <div class="form-group">
            <label for="content">Your Contribution:</label>
            <textarea name="content" id="content" class="form-control" rows="6" placeholder="Write your contribution here..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Submit Contribution</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
