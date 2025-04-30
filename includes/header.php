<?php
session_start();  

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$title = isset($_SESSION['title']) ? $_SESSION['title'] : '';
$displayName = htmlspecialchars(trim($title . ' ' . $name));
$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollaboraNation</title>
	<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar glass">
	<div class="container navbar-container">
		<a class="navbar-brand" href="index.php">CollaboraNation</a>
		<ul class="navbar-nav">
			<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
			<li class="nav-item"><a class="nav-link" href="projects.php">Projects</a></li>
			
			<?php
			if ($loggedIn) {
				echo '<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>';
			} else {
				echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
				echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
			}
			?>
		</ul>
		<button class="navbar-toggle" type="button">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>
</nav>

<!-- js for mobile menu toggle -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const navbarToggle = document.querySelector('.navbar-toggle');
		const navbarNav = document.querySelector('.navbar-nav');
		
		if (navbarToggle && navbarNav) {
			navbarToggle.addEventListener('click', function() {
				if (navbarNav.style.display === 'flex') {
					navbarNav.style.display = 'none';
				} else {
					navbarNav.style.display = 'flex';
				}
			});
		}
	});
</script>

<?php
require_once 'includes/project_functions.php';
displayFlash();
?>