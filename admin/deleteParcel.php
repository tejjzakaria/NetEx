<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";

// Get parcel ID from URL parameter
$parcelID = $_GET['id'];

// Delete parcel from database
$sql = "DELETE FROM parcels WHERE id='$parcelID'";
$result = mysqli_query($conn, $sql);

if($result) {
  // Redirect to parcels page
  header("Location: viewParcels.php");
  exit();
}
?>
