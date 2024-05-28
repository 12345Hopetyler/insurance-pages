<?php
require 'vendor/autoload.php'; // Include PHPMailer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "insurance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user registration data from POST request
    $Username = $_POST["Username"];
    $emailAddress = $_POST["emailAddress"];
    $password = generateRandomPassword(); // Generate a random password for the user (you can define this function)

    // Insert user into database
    $sql = "INSERT INTO users (Username, Email, Password) VALUES ('$Username', '$emailAddress', '$password')";

    if ($conn->query($sql) === TRUE) {
        // User inserted successfully, now send email with credentials
        try {
            // Create a PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings for Gmail SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'chirwahope00@gmail.com'; // Your Gmail address
            $mail->Password = 'cnkp styu qckt mxya'; // Your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Sender and recipient
            $mail->setFrom('chirwahope00@gmail.com', 'Hope Chirwa'); // Your name and email
            $mail->addAddress($emailAddress, $Username); // User's email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your Registration Details';
            $mail->Body = 'Dear ' . $Username . ',<br><br>' .
                          'You have been registered, and here are!<br>' .
                          'Your login credentials:<br>' .
                          'Username: ' . $Username . '<br>' .
                          'Password: ' . $password . '<br><br>' .
                          'Please keep these details safe and secure.<br><br>' .
                          'Regards,<br>Direct Insurance Agency';

            // Send email
            $mail->send();
            echo 'Registration successful! An email with your credentials has been sent.';
        } catch (Exception $e) {
            echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }

    // Close connection
    $conn->close();
}

// Function to generate a random password (you can modify this as needed)
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>

        