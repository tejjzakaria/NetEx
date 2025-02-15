<?php
session_start();

// Check if userID is set in the session
if (!isset($_SESSION['userID'])) {
    // If userID is not set, redirect to login page (index.php)
    header("Location: index.php");
    exit();
}

// If userID is set, the user is logged in, and the rest of the page will load normally
?>
