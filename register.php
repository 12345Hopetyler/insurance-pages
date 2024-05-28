
<?php

include "database/connection.php";
session_name("admin_session");
session_start();


// Include PHPMailer autoloader (adjust the path according to your setup)
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $UserName = $_POST['UserName'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $Phone = $_POST['Phone'];

    // Insert user data into database using prepared statement
    $insert_query = "INSERT INTO usercustomer (UserName, Email, Password, Phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $UserName, $Email, $Password, $Phone);

    if ($stmt->execute()) {
        // Record inserted successfully, now send an email to the user
        try {
            // Create a PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings for Gmail SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'chirwahope00@gmail.com'; 
            $mail->Password = 'cnkp styu qckt mxya'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Sender and recipient
            $mail->setFrom('chirwahope00@gmail.com', 'Hope Chirwa'); // Your name and email
            $mail->addAddress($Email, $UserName); // User's email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Our Website';
            $mail->Body = 'Dear ' . $UserName . ',<br><br>' .
                          'Thank you for registering with us!<br>' .
                          'Your login credentials:<br>' .
                          'Username: ' . $UserName . '<br>' .
                          'Password: ' . $Password . '<br><br>' .
                          'Please keep these details safe and secure.<br><br>' .
                          'Regards,<br>Your Company Name';

            // Send email
            $mail->send();
            echo "Record inserted successfully! An email with your credentials has been sent.";
        } catch (Exception $e) {
            echo "Error inserting record: " . $e->getMessage();
        }
    } else {
        echo "Error inserting record: " . $stmt->error;
    }

    // Close prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/back.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f8f9fa;
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            font-family: 'Arial', sans-serif; /* Choose a suitable font */
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Make the container full height of the viewport */
        }

        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }

        .card-header {
            background-color: #0d6efd;
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #167442;
            display: block; /* Ensure labels are on a new line */
        }

        input.form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-success {
            background-color: #0d6efd;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
  <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Registration</h2>
            </div>
            <div class="card-body">
                <form id="registrationForm" method="POST">
                    <div class="form-group">
                        <label for="UserName">Username:</label>
                        <input type="text" class="form-control" id="UserName" name="UserName" required>
                    </div>
                    <div class="form-group">
                        <label for="Email">Email:</label>
                        <input type="email" class="form-control" id="Email" name="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password:</label>
                        <input type="password" class="form-control" id="Password" name="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="Phone" style="color: green">Phone:</label>
                        <input type="tel" class="form-control" id="Phone" name="Phone" required> 
                    </div>
                    <button type="submit" class="btn btn-success" name="submit">Register</button>
                </form>
                <div class="mt-3"> <!-- Add margin top for spacing -->
                    <p>login after registration<a href="index.php">log in</a></p>
                </div>
            </div>
        </div>
    </div>
   
</body>
</html>
