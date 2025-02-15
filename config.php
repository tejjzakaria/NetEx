<?php
// Database connection details
$host = 'localhost';
$db = 'tejjmuab_vyn';
$user = 'tejjmuab_vyn';
$pass = 'Sr9g1c5e@';

// Create a new connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>