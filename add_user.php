<?php
session_start();
include 'includes/header.php';

//SWITCH THIS TO INCLUDES
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}


?>

<div class="container mt-5">
    <h2>Add New User</h2>
    <form action="add_user_process.php" method="post">
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" class="form-control" name="fullname" id="fullname" required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Temporary Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="student">Student</option>
                <option value="professor">Professor</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>