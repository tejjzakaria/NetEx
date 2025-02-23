<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../config.php"; // Database connection

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['ids']) || empty($data['ids'])) {
    echo json_encode(["success" => false, "message" => "No IDs provided"]);
    exit;
}

$ids = implode(",", array_map("intval", $data['ids'])); // Secure integer conversion

$sql = "DELETE FROM delivery_companies_api WHERE id IN ($ids)";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
