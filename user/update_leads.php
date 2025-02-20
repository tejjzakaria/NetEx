<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error_log.txt'); // Ensure to update this path

require '../vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

include "../config.php"; // Database connection
include "checkSession.php";
include "fetchUserData.php";

$credentialsPath = '../netex-451319-ebb845257e13.json';

$errorOccurred = false;
$errorMessage = '';

// Check if userID exists in the session
if (!$userID) {
    echo json_encode(["error" => "Aucun identifiant d'utilisateur trouvé dans la session."]);
    exit;
}

// Get all spreadsheet IDs associated with the user from the user_spreadsheets table
$sql = "SELECT spreadsheet_id FROM user_spreadsheets WHERE userID = ? AND status='ACTIVE'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// If no spreadsheets are found for the user, show a message
if ($result->num_rows === 0) {
    echo json_encode(["error" => "Aucune feuille de calcul n'est associée à votre compte."]);
    exit;
}

// Google Client Setup
$client = new Client();
$client->setApplicationName('NetEx');
$client->setAuthConfig($credentialsPath);
$client->setScopes([Sheets::SPREADSHEETS]);
$service = new Sheets($client);

$updated = 0;
$inserted = 0;

while ($row = $result->fetch_assoc()) {
    $spreadsheetId = $row['spreadsheet_id'];

    // Read data from Google Sheets
    $range = 'Sheet1'; // Adjust range if necessary
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if (count($values) > 0) {
        foreach ($values as $index => $row) {
            // Skip header row or empty rows
            if ($index === 0 || empty($row[0])) {
                continue;
            }

            $id = $row[0] ?? null;
            $tracking_id = $row[2] ?? "";
            $name = $row[3] ?? "";
            $phone_number = $row[4] ?? "";
            $address = $row[5] ?? "";
            $city = $row[6] ?? "";
            $product = $row[7] ?? "";
            $price = $row[8] ?? "";
            $agent = $row[9] ?? "";
            $comission = $row[10] ?? "";
            $comments = $row[11] ?? "";
            $status = $row[12] ?? "";

            if (empty($id)) {
                continue;
            }

            // Check if lead exists
            $checkSql = "SELECT id FROM leads WHERE id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $checkStmt->close();

            if ($checkResult->num_rows > 0) {
                // Update lead
                $sql = "UPDATE leads SET userID=?, name=?, tracking_id=?, phone_number=?, price=?, city=?, product=?, address=?, comments=?, agent=?, status=?, comission=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssssss", $userID, $name, $tracking_id, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission, $id);
                if ($stmt->execute()) {
                    $updated++;
                } else {
                    $errorOccurred = true;
                    $errorMessage = "Failed to update lead with ID: $id";
                }
                $stmt->close();
            } else {
                // Insert new lead
                $sql = "INSERT INTO leads (id, userID, name, tracking_id, phone_number, price, city, product, address, comments, agent, status, comission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssssss", $id, $userID, $name, $tracking_id, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission);
                if ($stmt->execute()) {
                    $inserted++;
                } else {
                    $errorOccurred = true;
                    $errorMessage = "Failed to insert lead with ID: $id";
                }
                $stmt->close();
            }
        }
    }
}

// After processing, check for errors:
if ($errorOccurred) {
    echo json_encode(['error' => $errorMessage]);
    exit;
}

// Return the response as JSON
$response = [
    'updated' => $updated,
    'inserted' => $inserted
];

header('Content-Type: application/json');
echo json_encode($response);
?>
