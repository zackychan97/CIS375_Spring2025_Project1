<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

include 'includes/header.php';
requireLogin(); 

// CAPTURE USER INFO FROM SESSION
$name = $_SESSION['name'] ?? '';
$email = $_SESSION['email'] ?? '';
?>

<div class="container mt-5">
    <h2>Edit Profile</h2>

    <form action="edit_profile_process.php" method="post">
        <div class="form-group mb-3">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" name="name" id="name"
                value="<?= htmlspecialchars($name) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" name="email" id="email"
                value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="new_password">New Password <small class="text-muted">(leave blank to keep current)</small></label>
            <input type="password" class="form-control" name="new_password" id="new_password">
        </div>

        <div class="form-group mb-4">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
