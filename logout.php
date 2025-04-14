<?php

//START SESSION IS REQUIRED TO DESTROY SESSION
session_start();

//CLEAR SESSION VARIABLES
$_SESSION = [];

//DESTROY SESSION
session_destroy();  

//REDIRECT TO LOGIN PAGE
header("Location: login.php"); // Redirect to the login page or home page
exit();