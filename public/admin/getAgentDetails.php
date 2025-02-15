<?php
include "../config.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM agent_info WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Determine the CSS class based on the status
        if ($row['status'] == 'ACTIVE') {
            $status_class = "badge bg-success fw-semibold fs-2";
        } else if ($row['status'] == 'INACTIVE') {
            $status_class = "badge bg-danger fw-semibold fs-2";
        } else {
            $status_class = "badge bg-primary fw-semibold fs-2";
        }
        
        // Add the status_class to the response array
        $row['status_class'] = $status_class;

        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}
?>
