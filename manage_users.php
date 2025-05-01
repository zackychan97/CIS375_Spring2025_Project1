<?php 
require_once 'includes/db.php';
require_once 'includes/auth.php';

include "includes/header.php";
requireAdmin();
// CAPTURE USER ROLE FROM SESSION, OR DEFAULT TO 'guest'
$role = $_SESSION['role'] ?? 'guest';


// PREPARE QUERY TO SELECT ALL USERS
$query = "SELECT id, name, email, role FROM users";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <div class="row">
        

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
                        echo "<td><a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-primary me-1 '>Edit</a>";
                        echo "<a href='delete_user.php?id=" . $user['id'] . "'class='btn btn-outline' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>