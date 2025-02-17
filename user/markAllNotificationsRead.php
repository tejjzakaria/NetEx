<?php
include "../config.php";
session_start();

$userID = $_SESSION['userID'];

// Mark all notifications as read
$query = "UPDATE notifications SET status = 'READ' WHERE userID = ? AND status = 'UNREAD'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();

// Get the updated unread count
$query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE userID = ? AND status = 'UNREAD'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unreadCount = $row['unread_count'];

// Return JSON response
echo json_encode(["status" => "success", "unread_count" => $unreadCount]);
?>
