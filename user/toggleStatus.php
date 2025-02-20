<?php
include "../config.php"; // Include your DB config
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    // Update the status in the database
    $sql = "UPDATE user_spreadsheets SET status = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'si', $status, $id); // 'si' means string and integer
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Redirect back to the page
    header('Location: viewSpreadsheets.php');
    exit();
}

mysqli_close($conn);
?>
