<?php
if (isset($_POST['ids'])) {
    $selectedIds = $_POST['ids']; // Array of selected IDs
    foreach ($selectedIds as $id) {
        // Process each selected row (e.g., delete or update in the database)
        echo "Processing ID: " . htmlspecialchars($id) . "<br>";
    }
} else {
    echo "No IDs received.";
}
?>
