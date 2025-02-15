<?php

include '../config.php';
// Get user data from database
$userID = $_SESSION['userID'];
$sql = "SELECT * FROM agent_info WHERE id='$userID'";
$result = mysqli_query($conn, $sql);
$userData = mysqli_fetch_assoc($result);

$agentName = $userData['full_name']
 
?>