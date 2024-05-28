<?php
include "database/connection.php";
session_name("client_session");
session_start();

// Include PHPMailer autoloader and necessary classes
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define the upload directory for police reports and damage pictures
$uploadDir = "C:/xampp/htdocs/clients/uploads/";
$claim_submitted = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $Issue = $_POST['Issue'];
    $UserName = $_POST['UserName'];
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $PolicyNumber = $_POST['PolicyNumber'];
    $Location = $_POST['Location'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $IncidentDate = $_POST['IncidentDate'];
    $Email = $_POST['Email'];

    // Handle police report upload
    if (!empty($_FILES['policeReport']['name'])) {
        $policeReportFileName = rand(1000, 10000) . "-" . $_FILES["policeReport"]["name"];
        $policeReportFilePath = $uploadDir . $policeReportFileName;
        if (move_uploaded_file($_FILES["policeReport"]["tmp_name"], $policeReportFilePath)) {
            $policeReportPath = $policeReportFileName;
        } else {
            echo "Error uploading police report.";
            exit(); // Exit if an error occurs
        }
    } else {
        $policeReportPath = ""; // No police report uploaded
    }

    // Handle damage pictures upload
    if (!empty($_FILES['damagePictures']['name'])) {
        $damagePicturesFileName = rand(1000, 10000) . "-" . $_FILES["damagePictures"]["name"];
        $damagePicturesFilePath = $uploadDir . $damagePicturesFileName;
        if (move_uploaded_file($_FILES["damagePictures"]["tmp_name"], $damagePicturesFilePath)) {
            $damagePicturesPath = $damagePicturesFileName;
        } else {
            echo "Error uploading damage pictures.";
            exit(); // Exit if an error occurs
        }
    } else {
        $damagePicturesPath = ""; // No damage pictures uploaded
    }

    // Prepare and execute the SQL query to insert data into the claims table
    $query = "INSERT INTO claims (Issue, UserName, FirstName, LastName, PolicyNumber, Location, PhoneNumber, IncidentDate, Email, policeReportPath, damagePicturesPath) 
              VALUES ('$Issue', '$UserName', '$FirstName', '$LastName', '$PolicyNumber', '$Location', '$PhoneNumber', '$IncidentDate', '$Email', '$policeReportPath', '$damagePicturesPath')";

    if (mysqli_query($conn, $query)) {
        // Set claim submitted to true
        $claim_submitted = true;

        // Send email to the user
        try {
            // Create a PHPMailer instance
            $mail = new PHPMailer(true);

            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
             $mail->Username = 'chirwahope00@gmail.com'; 
            $mail->Password = 'cnkp styu qckt mxya'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Set sender and recipient
            $mail->setFrom('chirwahope00@gmail.com', 'Hope Chirwa'); // Your name and email
            $mail->addAddress($Email, $UserName); // User's email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Claim Submission Confirmation';
            $mail->Body = 'Dear ' . $UserName . ',
                              <br><br>
                              Your claim has been successfully submitted.<br>
                              Thank you for choosing Direct Insurance.<br><br>
                              Regards,<br>Direct Insurance';

            // Send email
            $mail->send();
            echo "Record inserted successfully! An email confirmation has been sent.";
        } catch (Exception $e) {
            echo "Error sending email: " . $e->getMessage();
        }
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Insurance</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include "sidebar.php";?>
    <div class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="title">Make Claims</h2>
                        <h4 class="title">Fill the forms below</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-5 pr-md-1">
                                    <div class="form-group">
                                        <label>Incident Description</label>
                                        <input type="text" name="Issue" class="form-control" placeholder="Your issue">
                                    </div>
                                </div>
                                <div class="col-md-3 px-md-1"> 
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="UserName" class="form-control" placeholder="Username">
                                    </div>
                                </div>
                                <div class="col-md-4 pl-md-1">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">First Name</label>
                                        <input type="text" name="FirstName" class="form-control" placeholder="First Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-md-1">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="LastName" class="form-control" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="col-md-6 pl-md-1">
                                    <div class="form-group">
                                        <label>Policy Number</label>
                                        <input type="text" name="PolicyNumber" class="form-control" placeholder="Policy Number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-md-1">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" name="Location" class="form-control" placeholder="Location">
                                    </div>
                                </div>
                                <div class="col-md-6 pl-md-1">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="PhoneNumber" class="form-control" placeholder="Phone Number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Date of Incident</label>
                                        <input type="Date" class="form-control" name="IncidentDate" placeholder="Date">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="Email" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group file-upload-container">
                                    <div class="file-upload-section">
                                        <h6>Police Report</h6>
                                        <label for="policeReport">Police Report (if applicable):</label>
                                        <input type="file" class="form-control-file file-upload-input" id="policeReport" name="policeReport">
                                    </div>
                                    <div class="file-upload-section">
                                        <h6>Damage Pictures</h6>
                                        <label for="damagePictures">Damage Pictures (if applicable):</label>
                                        <input type="file" class="form-control-file file-upload-input" id="damagePictures" name="damagePictures">
                                    </div>
                                </div>
                           

 </div>
                            <div class="card-footer">
                                <button type="submit" name="submit" class="btn btn-fill btn-primary">Submit</button>
                              </div>
                            </form>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-user">
                    <div class="card-body">
                        <p class="card-text">
                            <div class="author">
                                <div class="block block-one"></div>
                                <div class="block block-two"></div>
                                <div class="block block-three"></div>
                                <div class="block block-four"></div>
                                <a href="javascript:void(0)">
                                    <img src="img/insurance.jpg" alt="..." width="300" height="200">
                                    <h5 class="title">Direct Insurance</h5>
                                </a>
                                <p class="description">
                                    We arrange all types of Insurance
                                </p>
                            </div>
                        </p>
                        <div class="card-description">
                            Direct agency is an Agency located within Blantyre Central Business District providing all your insurance needs under the provision of the insurance act 2010. It is a growing Malawian entity that strives to provide timely services to its clients.
                        </div>
                    </div>
                    <!-- footer start-->
                    <footer class="footer">
                        <div class="container-asfluid">
                            <div class="row">
                                <div class="col-md-6 p-0 footer-left">
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>

    <?php if ($claim_submitted): ?>
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Claim Submission Successful</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Your claim has been successfully submitted.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript to trigger the modal -->
    <script>
        $(document).ready(function(){
            $('#successModal').modal('show');
        });
    </script>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Claim Updates</h5>
                    </div>
                    <div class="card-body">
                        <!-- PHP code to fetch and display claim updates -->
                        <?php
                       

                        // Fetch claim updates for the current user
                        $current_user = $_SESSION['UserName'];
                        $query = "SELECT * FROM claims WHERE UserName = '$current_user'";
                        $result = mysqli_query($conn, $query);

                        // Check if there are any rows returned
                        if (mysqli_num_rows($result) > 0) {
                            // Output data of each row
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<p>Claim ID: " . $row['Id'] . "</p>";
                                echo "<p>Issue: " . $row['Issue'] . "</p>";
                                echo "<p>Status: " . $row['Status'] . "</p>";
                                echo "<hr>";
                            }
                        } else {
                            echo "<p>No claim updates found.</p>";
                        }

                        // Close the database connection
                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </div>
</body>
<style>
    img {
        border: 5px solid blue rgba(204, 204, 204, 0.5);; /* Add a solid border with 2px thickness and gray color */
        margin: 10px; /* Add 10px margin around the image */
        border-radius: 10px;
    }
    /* Style for the file upload container */
    .file-upload-container {
        display: flex;
        justify-content: space-between;
    }

    /* Style for each file upload section */
    .file-upload-section {
        flex: 0 0 48%; /* Adjust the width as needed */
    }

    /* Style for the file upload input */
    .file-upload-input {
        margin-bottom: 15px; /* Adjust spacing as needed */
    }

</style>
</html>
