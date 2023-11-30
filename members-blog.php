<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// If logged in, proceed with displaying the page

// configuration or database connection files

// Rest of blog page HTML and PHP code here
?>