<?php
include "../config.php";

if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    
    $sql = "SELECT id, store_name FROM user_stores WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo '<option value="">Sélectionner une boutique</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['store_name']) . '</option>';
    }
    
    $stmt->close();
}

$conn->close();
?>
