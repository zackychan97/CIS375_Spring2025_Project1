<?php 
require_once 'includes/db.php';
require_once 'includes/auth.php';

include "includes/header.php";
requireAdmin();
// CAPTURE USER ROLE FROM SESSION, OR DEFAULT TO 'guest'
$role = $_SESSION['role'] ?? 'guest';


// PREPARE QUERY TO SELECT ALL CONTACT MESSAGES
$query = "SELECT id, name, email, subject, message FROM contact_messages";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <div class="row">
        

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