<?php
session_start();

// Check if the session variable containing the username exists
if(isset($_SESSION['UserName'])) {
    // Retrieve the username from the session
    $senderName = $_SESSION['UserName'];

    // Return the sender's name as the response
    echo $senderName;
} else {
    // If the session username doesn't exist, return an empty string or handle the error as needed
    echo ""; // You can return an empty string or handle the error in a different way
}
?>
