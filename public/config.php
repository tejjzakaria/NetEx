<?php
// Database connection details
$host = 'localhost';
$db = 'vyn';
$user = 'root';
$pass = '';

// Create a new connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>