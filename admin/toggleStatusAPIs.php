<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";

// Check if the required GET parameters are present
if (isset($_GET['id']) && isset($_GET['status'])) {
    $status_id = $_GET['id'];
    $new_status = $_GET['status'];

    // Ensure the new status is valid
    if ($new_status == 'ACTIVE' || $new_status == 'INACTIVE') {

        // Update the visibility based on the new status
        $sql = "UPDATE delivery_companies_api SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $new_status, $status_id);

        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect back to the page that lists the statuses
            header('Location: viewAPIs.php'); // Change this to your actual status page URL
            exit();
        } else {
            // Handle error (optional)
            echo "Error updating status.";
        }
    } else {
        // Handle invalid status (optional)
        echo "Invalid status.";
    }
} else {
    // Handle missing parameters (optional)
    echo "Missing parameters.";
}

mysqli_close($conn);
?>
