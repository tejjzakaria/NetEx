<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";

if (isset($_POST['id']) && isset($_POST['spreadsheet_id'])) {
    $id = $_POST['id'];
    $api_key = $_POST['spreadsheet_id']; // Rename variable for clarity

    $sql = "UPDATE delivery_companies_api SET api_key = ? WHERE id = ?"; // Corrected column name
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'si', $api_key, $id);
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

mysqli_close($conn);
?>