<?php
include "../config.php"; // Database connection

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['id'])) {
    $id = $data['id'];
    $name = strtoupper($data['name']);
    $phone_number = strtoupper($data['phone_number']);
    $address = strtoupper($data['address']);
    $city = strtoupper($data['city']);
    $product = strtoupper($data['product']);
    $price = strtoupper($data['price']);
    $agent = strtoupper($data['agent']);
    $status = strtoupper($data['status']);
    $comission = strtoupper($data['comission']);
    $comments = strtoupper($data['comments']);

    // Check if the row already exists
    $check_sql = "SELECT * FROM leads WHERE id='$id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Update existing row
        $sql = "UPDATE leads 
                SET name='$name', phone_number='$phone_number', address='$address', city='$city', 
                    product='$product', price='$price', agent='$agent', status='$status', 
                    comission='$comission', comments='$comments' 
                WHERE id='$id'";
    } else {
        // Insert new row
        $sql = "INSERT INTO leads (id, name, phone_number, address, city, product, price, agent, status, comission, comments) 
                VALUES ('$id', '$name', '$phone_number', '$address', '$city', '$product', '$price', '$agent', '$status', '$comission', '$comments')";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["error" => "Invalid data"]);
}
?>
