<?php include "includes/header.php"; ?>

<div class="container py-5">
	<div class="form-container glass">
		<h2 class="text-center mb-4">Login</h2>
		<form action="login_process.php" method="post">
			<div class="form-group">
				<label for="email" class="form-label">Email Address</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
			</div>
			<div class="form-group">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
			</div>
			<div class="text-center mt-4">
				<button type="submit" class="btn btn-primary">Login</button>
			</div>
		</form>
		<p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
	</div>
</div>

<?php include "includes/footer.php"; ?>
