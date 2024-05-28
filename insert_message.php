<?php
include "database/connection.php";
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender = isset($_POST["sender"]) ? $_POST["sender"] : "";
    $receiver = isset($_POST["receiver"]) ? $_POST["receiver"] : "";
    $message = isset($_POST["message"]) ? $_POST["message"] : "";

    // Insert message into the database
    $sql = "INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?)";
    
    // Create a prepared statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters
    $stmt->bind_param("sss", $sender, $receiver, $message);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Message inserted successfully";
    } else {
        echo "Error inserting message: " . $conn->error;
    }
    
    // Close the statement
    $stmt->close();
}

$conn->close();
?>
