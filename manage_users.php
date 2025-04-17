<?php 
session_start(); 
require_once 'includes/db.php';
include "includes/header.php";

// CAPTURE USER ROLE FROM SESSION, OR DEFAULT TO 'guest'
$role = $_SESSION['role'] ?? 'guest';

// CHECK IF USER IS ADMIN, IF NOT, REDIRECT TO DASHBOARD
if ($role !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// PREPARE QUERY TO SELECT ALL USERS
$query = "SELECT id, name, email, role FROM users";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
                <a href="projects.php" class="list-group-item list-group-item-action">Browse Projects</a>
                <a href="#" class="list-group-item list-group-item-action">Edit Profile</a>
                <?php
                if ($role === 'admin') {
                    echo '<a href="manage_users.php" class="list-group-item list-group-item-action text-info">Admin: Manage Users</a>';
                    echo '<a href="manage_projects.php" class="list-group-item list-group-item-action text-info">Admin: Manage Projects</a>';
                    echo '<a href="manage_comments.php" class="list-group-item list-group-item-action text-info">Admin: Manage Comments</a>';
                }
                ?>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h2>Manage Users</h2>
            <a href="add_user.php" class="btn btn-success mb-3">Add New User</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($user = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                        // CAN SOMEONE FIGURE OUT SPACING THESE BUTTONS OUT?
                        // I TRIED USING ME-1, BUT IT DIDN'T WORK
                        echo "<td><a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-primary me-1 '>Edit</a>";
                        echo "<a href='delete_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>