<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];
$newStatus = $data['status'];

if (!empty($ids) && in_array($newStatus, ['ACTIVE', 'INACTIVE'])) {
    $idsString = implode(',', array_map('intval', $ids));
    $sql = "UPDATE delivery_companies_api SET status = ? WHERE id IN ($idsString)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $newStatus);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>