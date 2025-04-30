<?php include "includes/header.php"; ?>
<?php require_once "includes/auth.php"; ?>
<?php requireLogin(); ?>

<div class="container mt-4">
    <h2>Upload a File</h2>

    <form action="upload_process.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="project_id">Project ID (optional):</label>
            <input type="number" class="form-control" id="project_id" name="project_id" placeholder="Enter Project ID">
        </div>

        <div class="form-group">
            <label for="contribution_id">Contribution ID (optional):</label>
            <input type="number" class="form-control" id="contribution_id" name="contribution_id" placeholder="Enter Contribution ID">
        </div>

        <div class="form-group">
            <label for="upload_file">Choose File:</label>
            <input type="file" class="form-control-file" id="upload_file" name="upload_file" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Upload File</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
