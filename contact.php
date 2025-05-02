<?php include "includes/header.php"; ?>

	<div class="container mt-4">
		<h2>Contact Us</h2>
		<p>If you have any questions, comments, or feedback about CollaboraNation, please fill out the form below and we'll get back to you as soon as possible.</p>
		<form action="contact_process.php" method="post">
			<div class="form-group">
				<label for="name">Full Name:</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
			</div>
			<div class="form-group">
				<label for="email">Email Address:</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
			</div>
			<div class="form-group">
				<label for="subject">Subject:</label>
				<input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
			</div>
			<div class="form-group">
				<label for="message">Message:</label>
				<textarea class="form-control" id="message" name="message" rows="5" placeholder="Your message..." required></textarea>
			</div>
			<button type="submit" class="btn btn-primary btn-outline">Send Message</button>
		</form>
	</div>

	<?php include "includes/footer.php"; ?>
