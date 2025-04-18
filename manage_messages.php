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

// PREPARE QUERY TO SELECT ALL CONTACT MESSAGES
$query = "SELECT id, name, email, subject, message FROM contact_messages";
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
                    echo '<a href="manage_messages.php" class="list-group-item list-group-item-action text-info">Admin: Manage Messages</a>';
                }
                ?>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h2>Manage Contact</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($message = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($message['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($message['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($message['subject']) . "</td>";
                        echo "<td>" . htmlspecialchars($message['message']) . "</td>";            
                        echo "<td><a href='delete_message.php?id=" . $message['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this contact message?\")'>Delete</a></td>";                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>