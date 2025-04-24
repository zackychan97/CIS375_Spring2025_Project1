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
	<link rel="stylesheet" href="assets/style.css">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollaboraNation</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="index.php">CollaboraNation</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
			<li class="nav-item"><a class="nav-link" href="projects.php">Projects</a></li>
			

			<?php
			if ($loggedIn) {
				echo '<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>';
				

				

				echo '<li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>';
			} else {
				echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
				echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
			}
			?>
		</ul>
	</div>
</nav>
<?php

require_once 'includes/project_functions.php';

displayFlash();
?>