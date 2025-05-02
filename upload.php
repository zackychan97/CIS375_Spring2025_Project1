<?php include "includes/header.php";
require_once "includes/auth.php";
//JS: CLEANED UP TAGS AND ADDED DB CONNECTION
require_once 'includes/db.php';
requireLogin();


//JS: SET USER ID FOR QUERY USAGE
$user_id = $_SESSION['user_id'] ?? null;
//JS: PREPARED STATEMENT TO GATHER PROJECTS FOR USER
$projectQuery = " SELECT projects.id, title FROM projects 
                JOIN project_members ON projects.id = project_members.project_id 
                WHERE project_members.user_id = ?";
$stmt = mysqli_prepare($conn, $projectQuery);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);


?>

<div class="container mt-5">
    <div class="form-container glass">
        <h2 class="text-center mb-4">Upload a File</h2>

        <form action="upload_process.php" method="post" enctype="multipart/form-data">
            <!-- JS: NEW SELECTOR FOR PROJECT BASED ON QUERY -->
            <div class="form-group mb-3">
                <label for="project_id" class="form-label">Select Project:</label>
                <select
                    name="project_id"
                    id="project_id"
                    class="form-control"
                    required>
                    <option value="">-- Choose a project --</option>
                    <?php foreach ($projects as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- <div class="form-group mb-3">
                <label for="project_id" class="form-label">Project ID (optional):</label>
                <input type="number" class="form-control" id="project_id" name="project_id" placeholder="Enter Project ID">
            </div> -->

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