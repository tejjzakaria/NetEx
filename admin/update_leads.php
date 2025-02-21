<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error_log.txt'); // Ensure to update this path

require '../vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

include "../config.php"; // Database connection

$credentialsPath = '../netex-451319-ebb845257e13.json';

$errorOccurred = false;
$errorMessage = '';

// Admin can access all spreadsheets, so no need for session-based userID check

// Get all spreadsheet IDs from user_spreadsheets table
$sql = "SELECT spreadsheet_id FROM user_spreadsheets WHERE status='ACTIVE'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// If no spreadsheets are found, show a message
if ($result->num_rows === 0) {
    echo json_encode(["error" => "Aucune feuille de calcul trouvée dans la base de données."]);
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
            if ($index === 0 || empty($row[0])) { // Ensure tracking_id is not empty
                continue;
            }
            $name = $row[0] ?? "";
            $tracking_id = $row[1] ?? "";
            
            $phone_number = $row[2] ?? "";
            $address = $row[3] ?? "";
            $city = $row[4] ?? "";
            $product = $row[5] ?? "";
            $price = $row[6] ?? "";
            $agent = $row[7] ?? "";
            $comission = $row[8] ?? "";
            $comments = $row[9] ?? "";
            $status = $row[10] ?? "";

            // Ensure tracking_id is valid before proceeding
            if (empty($tracking_id)) {
                continue;
            }

            // Check if lead exists by tracking_id (not id)
            $checkSql = "SELECT id FROM leads WHERE tracking_id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $tracking_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $checkStmt->close();

            if ($checkResult->num_rows > 0) {
                // Update existing lead
                $sql = "UPDATE leads SET name=?, phone_number=?, price=?, city=?, product=?, address=?, comments=?, agent=?, status=?, comission=? WHERE tracking_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssss", $name, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission, $tracking_id);

                if ($stmt->execute()) {
                    $updated++;
                } else {
                    $errorOccurred = true;
                    $errorMessage = "Failed to update lead with Tracking ID: $tracking_id";
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
