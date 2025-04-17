<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';


//ADMIN CHECK - SWITCH THIS TO INCLUDES
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

//CAPTURE USER ID FROM URL
$userId = $_GET['id'] ?? null;
if (!$userId) {
    header("Location: manage_users.php");
    exit();
}

//SELECT USER FROM DATABASE BASED ON ID FROM URL
$query = "SELECT id, name, email, role FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

//CHECK IF USER EXISTS
if (!$user) {
    echo "User not found.";
    exit();
}
?>

<div class="container mt-5">
    <h2>Edit User</h2>
    <form action="edit_user_process.php" method="post">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="form-group">
            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" class="form-control">
                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                <option value="professor" <?= $user['role'] === 'professor' ? 'selected' : '' ?>>Professor</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

