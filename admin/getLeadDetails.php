<?php
include "../config.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM leads WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Determine the CSS class based on the status
        if ($row['status'] == 'Confirmer') {
            $status_class = "badge bg-success";
        } else if ($row['status'] == 'rappel') {
            $status_class = "badge bg-warning";
        } else if ($row['status'] == 'boite vocale') {
            $status_class = "badge bg-danger";
        } else if ($row['status'] == 'pas de réponse') {
            $status_class = "badge bg-warning";
        } else if ($row['status'] == 'occupé') {
            $status_class = "badge bg-warning";
        } else if ($row['status'] == 'annulé') {
            $status_class = "badge bg-warning";
        } else if ($row['status'] == 'message whatsapp') {
            $status_class = "badge bg-warning";
        } else {
            $status_class = "badge bg-primary";
        }
        
        // Add the status_class to the response array
        $row['status_class'] = $status_class;

        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Lead not found']);
    }
}
?>
