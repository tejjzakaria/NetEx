<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";

if (isset($_GET['country'])) {
    $country = $_GET['country'];

    $sql = "SELECT 
                shipping_delivery_fee, 
                shipping_return_fee, 
                shipping_cod_fee,
                call_center_product_type,
                call_center_lead_fee,
                call_center_confirmation_fee,
                call_center_delivery_fee,
                call_center_upsell_fee,
                currency
            FROM simulator_fees WHERE country = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $country);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, 
                                $delivery_fee, 
                                $return_fee, 
                                $cod_fee, 
                                $product_type,
                                $lead_fee,
                                $confirmation_fee,
                                $delivery_call_fee,
                                $upsell_fee,
                                $currency);

        if (mysqli_stmt_fetch($stmt)) {
            $data = [
                'shipping_delivery_fee' => $delivery_fee,
                'shipping_return_fee' => $return_fee,
                'shipping_cod_fee' => $cod_fee,
                'call_center_product_type' => $product_type,
                'call_center_lead_fee' => $lead_fee,
                'call_center_confirmation_fee' => $confirmation_fee,
                'call_center_delivery_fee' => $delivery_call_fee,
                'call_center_upsell_fee' => $upsell_fee,
                'currency' => $currency
            ];
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Country not found']);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'Country parameter missing']);
}

mysqli_close($conn);
?>