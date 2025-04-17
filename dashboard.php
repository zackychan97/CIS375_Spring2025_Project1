<?php 
session_start(); 
include "includes/header.php";

// CAPTURE USER ROLE FROM SESSION, OR DEFAULT TO 'guest'
$role = $_SESSION['role'] ?? 'guest';


//REMOVE THIS LATER, FOR TESTING PURPOSES ONLY
//$_SESSION['role'] = 'admin';


?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
                <a href="projects.php" class="list-group-item list-group-item-action">Browse Projects</a>
                <a href="#" class="list-group-item list-group-item-action">Edit Profile</a>
                <?php if ($role === 'admin') {
                echo '<a href="manage_users.php" class="list-group-item list-group-item-action text-info">Admin: Manage Users</a>';
                echo '<a href="manage_projects.php" class="list-group-item list-group-item-action text-info">Admin: Manage Projects</a>';
                echo '<a href="manage_comments.php" class="list-group-item list-group-item-action text-info">Admin: Manage Comments</a>';
}
?>
                <!-- LOGOUT ADDED, REDIRECT TO LOGOUT.PHP -->
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-lg p-4">
                <h2 class="text-center">Welcome, John Doe!</h2>
                <p class="text-muted text-center"><strong>Username:</strong> JohnDOHHH!</p>

                <!-- User Projects Section -->
                <h3 class="mt-4">Your Projects</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">AI Chatbot</h5>
                                <p class="card-text"><strong>Role:</strong> Developer</p>
                                <a href="project.php?title=AI%20Chatbot" class="btn btn-primary">View Project</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Cybersecurity Audit</h5>
                                <p class="card-text"><strong>Role:</strong> Research Assistant</p>
                                <a href="project.php?title=Cybersecurity%20Audit" class="btn btn-primary">View Project</a>
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Project pagination">
			<ul class="pagination">
				<li class="page-item"><a class="page-link" href="#">Previous</a></li>
				<li class="page-item"><a class="page-link" href="#">1</a></li>
				<li class="page-item"><a class="page-link" href="#">2</a></li>
				<li class="page-item"><a class="page-link" href="#">Next</a></li>
			</ul>
		</nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
