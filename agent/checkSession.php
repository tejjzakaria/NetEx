<?php
session_start();

// Define session timeout duration (e.g., 30 minutes)
$timeout_duration = 30 * 60; // 30 minutes in seconds

// Check if userID is set in the session
if (!isset($_SESSION['userID'])) {
    // If userID is not set, redirect to login page (index.php)
    header("Location: index.php");
    exit();
}

// Check if the session has timed out
$current_time = time();
if (isset($_SESSION['login_timestamp']) && ($current_time - $_SESSION['login_timestamp']) > $timeout_duration) {
    // Session has expired, clear session data and redirect to login page
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Update the login timestamp to keep the session alive
$_SESSION['login_timestamp'] = $current_time;

// If userID is set and session is valid, the rest of the page will load normally
?>
