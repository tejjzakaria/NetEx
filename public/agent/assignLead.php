<?php
session_start(); // Start the session

// Include database connection and user data
include '../config.php'; 
include 'fetchUserData.php'; // Fetch the current user's full name

$alertScript = "";

// Get the lead ID from the URL
$lead_id = $_GET['id'];

// Escape special characters to prevent SQL injection
$lead_id = mysqli_real_escape_string($conn, $lead_id);

// Prepare the SQL statement to update the agent column
$sql = "UPDATE leads SET agent = '$agentName' WHERE id = '$lead_id'";

// Execute the query
if (mysqli_query($conn, $sql)) {
    // SweetAlert script for success
    $alertScript = "
    <script>
        Swal.fire({
          title: 'Lead attribué avec succès', 
          text: 'Redirection vers la liste des leads...',
          icon: 'success',
          timer: 3000, 
          showConfirmButton: false
        }).then(() => {
          window.location.href = 'viewLeads.php'; // Redirect after the SweetAlert
        });
    </script>
    ";
} else {
    // If the query fails, show an error message
    $alertScript = "<div class='alert alert-danger'>Error updating lead: " . mysqli_error($conn) . "</div>";
}

mysqli_close($conn);

// Output the HTML
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Assignment</title>
    <link rel="shortcut icon" type="image/png" href="dist/images/logos/favicon.ico" />
    <link id="themeColors" rel="stylesheet" href="dist/css/style.min.css" />
    <link rel="stylesheet" href="dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="dist/libs/sweetalert2/dist/sweetalert2.min.css">
</head>
<body>


<script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="dist/js/forms/sweet-alert.init.js"></script>

<?php
    // Display the SweetAlert or error message
    echo $alertScript;
?>


</body>
</html>
