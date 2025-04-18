<?php include "includes/header.php"; ?>

<div class="container mt-4">
	<h2>Register</h2>
	<form action="register_process.php" method="post">
		<div class="form-group">
			<label for="fullname">Full Name: </label>
			<input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name" required>
		</div>
		<div class="form-group">
			<label for="email">Email Address: </label>
			<input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
		</div>
		<div class="form-group">
			<label for="password">Password: </label>
			<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
		</div>
		<div class="form-group">
			<label for="confirm_password">Confirm Password: </label>
			<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
		</div>
		<div class="form-group">
			<label>Role: </label><br>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="student" name="role" value="student" required>
				<label class="form-check-label" for="student">Student</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="professor" name="role" value="professor">
				<label class="form-check-label" for="professor">Professor</label>
			</div>
		</div>
		<button type="submit" class="btn btn-success">Register</button>
	</form>
	<p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
</div>

<?php include "includes/footer.php"; ?>