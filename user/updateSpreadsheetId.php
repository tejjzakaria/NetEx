<?php
// Include database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../config.php";

// Check if ID and new spreadsheet_id are passed
if (isset($_POST['id']) && isset($_POST['spreadsheet_id'])) {
    $id = $_POST['id'];
    $spreadsheet_id = $_POST['spreadsheet_id'];

    // Update the spreadsheet_id in the database
    $sql = "UPDATE user_spreadsheets SET spreadsheet_id = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'si', $spreadsheet_id, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

// Close the connection
mysqli_close($conn);
?>
