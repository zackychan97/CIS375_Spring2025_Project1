<?php
//DON'T FORGET INLCUDES FOR DB

//DATABASE CONNECTION
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'collab_db';


//ESTABLISH CONNECTION
$conn = mysqli_connect($host, $user, $password, $database);


//CHECK CONNECTION
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        // echo "Connected successfully";
    }