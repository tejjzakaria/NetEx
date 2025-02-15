<?php
include "../config.php";

if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];  // Get userID from AJAX request

    // Prepare SQL query to get parcels for the selected user
    $sql = "SELECT id, tracking_id, recipient, address FROM parcels WHERE userID = ? AND status='new'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    $options = '';
    if ($result->num_rows > 0) {
        // Loop through the parcels and create options
        while ($row = $result->fetch_assoc()) {
            $parcelID = htmlspecialchars($row['id']); // Parcel ID
            $trackingID = htmlspecialchars($row['tracking_id']); // Parcel tracking number
            $recipient = htmlspecialchars($row['recipient']); // Parcel tracking number
            $address = htmlspecialchars($row['address']);
            $options .= "<option value='$recipient, $trackingID, $address'>$recipient | $trackingID | $address</option>";
        }
    } else {
        $options .= "<option value=''>No parcels available</option>"; // If no parcels found
    }

    echo $options; // Return the options as the AJAX response
}
?>
