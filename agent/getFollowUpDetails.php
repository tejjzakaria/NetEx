<?php
include "../config.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM notifications WHERE leadID = $id ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    $followUps = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Determine the CSS class based on the status
        if ($row['status'] == 'CONFIRMER' || $row['status'] == 'RAMMASSER') {
            $status_class = "badge bg-success fw-semibold fs-2";
        } else if (in_array($row['status'], ['RAPPEL', 'PAS DE RÉPONSE', 'OCCUPÉ', 'ANNULÉ', 'MESSAGE WHATSAPP'])) {
            $status_class = "badge bg-warning fw-semibold fs-2";
        } else if ($row['status'] == 'BOITE VOCALE') {
            $status_class = "badge bg-danger fw-semibold fs-2";
        } else {
            $status_class = "badge bg-primary fw-semibold fs-2";
        }
        
        // Add the status_class to the row
        $row['status_class'] = $status_class;
        $followUps[] = $row;
    }

    if (!empty($followUps)) {
        echo json_encode($followUps);
    } else {
        echo json_encode(['error' => 'Aucun suivi trouvé']);
    }
}
?>
