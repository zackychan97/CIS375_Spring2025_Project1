<?php include "includes/header.php"; ?>
<?php require_once "includes/auth.php"; ?>
<?php requireLogin(); ?>

<?php
$project_id = $_GET['id'] ?? null;
if (!$project_id) {
    echo "Invalid project ID.";
    include "includes/footer.php";
    exit();
}
?>

<div class="container mt-4">
    <h4>Leave a Comment</h4>
    <form action="comment_process.php" method="POST">
        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>">

        <div class="form-group">
            <textarea name="content" class="form-control" placeholder="Write your comment..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
