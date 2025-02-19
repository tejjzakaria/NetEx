<?php

include "../config.php"; // Your DB connection
include "fetchUserData.php";

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Update the user's last activity
    $update_activity_query = "UPDATE user_info SET last_activity = NOW() WHERE id = $userID";
    mysqli_query($conn, $update_activity_query);
}


?>