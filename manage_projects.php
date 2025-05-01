<?php 
require_once 'includes/db.php';
require_once 'includes/auth.php';

include "includes/header.php";
requireAdmin();
$role = $_SESSION['role'] ?? 'guest';

// JOIN TO GET PROJECTS AND FACULTY LEADS
$query = "
    SELECT 
        projects.id, 
        projects.title, 
        projects.college, 
        users.name AS mentor_name, 
        users.title AS mentor_title 
    FROM projects 
    JOIN users ON projects.faculty_mentor_id = users.id
";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <div class="row">
        

        <!-- Main Content -->
        <div class="col-md-9">
            <h2>Manage Projects</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Mentor</th>
                        <th>College</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($project = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($project['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($project['college']) . "</td>";
                        echo   "<td>" . htmlspecialchars($project['mentor_title'] . ' ' . $project['mentor_name']) . "</td>";
                        echo "<td><a href='edit_project.php?id=" . $project['id'] . "' class='btn btn-sm btn-outline me-1 '>Edit</a>";
                        echo "<a href='delete_project.php?id=" . $project['id'] . "' class='btn btn-sm btn-outline' onclick='return confirm(\"Are you sure you want to delete this project?\")'>Delete</a>";                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>