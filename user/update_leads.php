<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

$credentialsPath = __DIR__ . '/netex-451319-179d74138e95.json';

$client = new Client();
$client->setApplicationName('NetEx');
$client->setAuthConfig($credentialsPath);
$client->setScopes([Sheets::SPREADSHEETS]);

$service = new Sheets($client);
$spreadsheetId = '1lolmwm-abzsWW0IHzsEnuLD3YgLi_AMZilumuzOH5GY';


$range = 'Sheet1';

$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

include "../config.php";

$updated = 0;
$inserted = 0;

if (count($values) > 0) {
    for ($i = 1; $i < count($values); $i++) {
        $row = $values[$i];

        $id = $row[0] ?? null;
        $userID = $row[1] ?? 1;
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

        $checkSql = "SELECT id FROM leads WHERE id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkStmt->close();

        if ($checkResult->num_rows > 0) {
            $sql = "UPDATE leads SET userID=?, name=?, tracking_id=?, phone_number=?, price=?, city=?, product=?, address=?, comments=?, agent=?, status=?, comission=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssss", $userID, $name, $tracking_id, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission, $id);
            if ($stmt->execute()) {
                $updated++;
            }
        } else {
            $sql = "INSERT INTO leads (id, userID, name, tracking_id, phone_number, price, city, product, address, comments, agent, status, comission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssss", $id, $userID, $name, $tracking_id, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission);
            if ($stmt->execute()) {
                $inserted++;
            }
        }

        $stmt->close();
    }
}

$conn->close();

// Send JSON response
echo json_encode(["updated" => $updated, "inserted" => $inserted]);

?>

