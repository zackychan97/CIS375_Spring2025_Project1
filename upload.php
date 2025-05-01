<?php include "includes/header.php"; ?>
<?php require_once "includes/auth.php"; ?>
<?php requireLogin(); ?>

<div class="container mt-5">
    <div class="form-container glass">
        <h2 class="text-center mb-4">Upload a File</h2>
        
        <form action="upload_process.php" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="project_id" class="form-label">Project ID (optional):</label>
                <input type="number" class="form-control" id="project_id" name="project_id" placeholder="Enter Project ID">
            </div>

            <div class="form-group mb-3">
                <label for="contribution_id" class="form-label">Contribution ID (optional):</label>
                <input type="number" class="form-control" id="contribution_id" name="contribution_id" placeholder="Enter Contribution ID">
            </div>

            <div class="form-group mb-4">
                <label for="upload_file" class="form-label">File Upload:</label>
                <input type="file" class="form-control" id="upload_file" name="upload_file" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-secondary px-4 py-2">Done</button>
            </div>
        </form>
    </div>
</div>

<?php include "includes/footer.php"; ?>
