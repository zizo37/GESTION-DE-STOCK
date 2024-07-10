<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Delete the notification with the provided ID
    $sql = "DELETE FROM notification WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Notification deleted successfully
        header("Location: notification.php?success=1");
        exit();
    } else {
        // Error occurred while deleting the notification
        header("Location: notification.php?error=1");
        exit();
    }
} else {
    // ID parameter not provided
    header("Location: notification.php");
    exit();
}

?>