<?php
include "database/connection.php";
if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Prepare the SQL statement
    $sql = "DELETE FROM claims WHERE id = ?";
    
    // Create a prepared statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameter
    $stmt->bind_param("i", $id);
    
    // Execute the statement
    $stmt->execute();
    
    // Close the statement
    $stmt->close();
}
// Redirect to the view_claims.php page after deletion
header('location: view_claims.php');
exit;
?>


