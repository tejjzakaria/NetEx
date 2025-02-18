<?php

require __DIR__ . '/vendor/autoload.php'; // Composer autoload

use Google\Client;
use Google\Service\Sheets;

// Your credentials file path
$credentialsPath = './netex-credentials.json';

// Create the client
$client = new Client();
$client->setApplicationName('NetEx'); // Give it a name
$client->setAuthConfig($credentialsPath);
$client->setScopes([Sheets::SPREADSHEETS]); // Specify the scope

// Create the Sheets service
$service = new Sheets($client);

// The ID of your spreadsheet
$spreadsheetId = '18zWPZt1IzhVf0mLyb6ohTALzTbOj7DJCpAt07BfT4ds';

// The range of cells to read (e.g., 'Sheet1!A1:K')
$range = 'Sheet1!A2:K1000'; // Or specify a more precise range

// Get the values from the spreadsheet
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

// MySQL Connection
include "../config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (count($values) > 0) {
    for ($i = 1; $i < count($values); $i++) {
        $row = $values[$i];

        // Prepare the data for MySQL (handle potential missing values)
        $id = $row[0] ?? null;
        $name = $row[1] ?? null;  // Changed variable name for clarity
        $phone_number = $row[2] ?? null; // Changed variable name
        $address = $row[3] ?? null; // Changed variable name
        $city = $row[4] ?? null;
        $product = $row[5] ?? null;
        $price = $row[6] ?? null;
        $agent = $row[7] ?? null;
        $status = $row[8] ?? null;
        $comission = $row[9] ?? null; // Corrected spelling
        $comments = $row[10] ?? null;
        $tracking_id = $row[11] ?? null; // Added tracking_id

        // Check if the ID exists in the database
        $checkSql = "SELECT id FROM leads WHERE id = ?"; // Corrected column name
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkStmt->close(); // Close the check statement


        if ($checkResult->num_rows > 0) {
            // Update (excluding userID and created_at)
            $sql = "UPDATE leads SET name=?, tracking_id=?, phone_number=?, price=?, city=?, product=?, address=?, comments=?, agent=?, status=?, comission=? WHERE id=?"; // Corrected column names
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssss", $name, $tracking_id, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission, $id); // Corrected bind_param order and types
        } else {
            // Insert (excluding userID and created_at)
            $sql = "INSERT INTO leads (id, name, tracking_id, phone_number, price, city, product, address, comments, agent, status, comission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Corrected column names
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssss", $id, $name, $tracking_id, $phone_number, $price, $city, $product, $address, $comments, $agent, $status, $comission); // Corrected bind_param order and types
        }

        if ($stmt->execute()) {
            // echo "Record updated/inserted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    echo "0 results";
}

$conn->close();


?>
