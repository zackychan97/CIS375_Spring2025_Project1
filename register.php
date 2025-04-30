<?php include "includes/header.php"; ?>

<div class="container py-5">
    <div class="form-container glass">
        <h2 class="text-center mb-4">Create an Account</h2>
        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="title" class="form-label">Title (optional)</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="e.g., Dr., Prof., Mr., Ms.">
            </div>
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <div class="d-flex mt-2">
                    <div class="form-check me-4">
                        <input class="form-check-input" type="radio" id="student" name="role" value="Student" required>
                        <label class="form-check-label" for="student">Student</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="professor" name="role" value="Professor">
                        <label class="form-check-label" for="professor">Professor</label>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>