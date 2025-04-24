<?php

// CHECK IF USER IS LOGGED IN
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

// VERIFY USER ROLE = ADMIN
function requireAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}