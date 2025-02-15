<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
$message = "";
// Check if the lead ID is set in the POST request
if (isset($_POST['id'])) {
    $withdrawalID = $_POST['id']; // Get lead ID from POST

    // Prepare and execute the delete query
    $sql = "DELETE FROM agent_withdrawal_requests WHERE id='$withdrawalID'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect to the view leads page
        header("Location: viewWalletAgent.php");
        exit();
    } else {
        // Handle error (optional)
        $message = '<div class="alert alert-danger">Error deleting record:</div>';
    }
} else {
    // Handle case where ID is not set (optional)
    $message = '<div class="alert alert-danger">No lead ID specified.</div>';
}
?>
