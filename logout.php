<?php
require_once 'includes/project_functions.php';

//START SESSION IS REQUIRED TO DESTROY SESSION
session_start();

//CLEAR SESSION VARIABLES
$_SESSION = [];

//DESTROY SESSION
session_destroy();  

//REDIRECT TO LOGIN PAGE
flashMessage("You have been logged out.", "success");
header("Location: login.php"); 
exit();