<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="style.css">
<div class="container mt-4">
    <h2>Login</h2>
    <form action="login_process.php" method="post">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
</div>

</html>
<?php include 'footer.php'; ?>
