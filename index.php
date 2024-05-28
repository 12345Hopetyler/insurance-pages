<?php
// Include the database connection file
include "database/connection.php";
session_name("client_session");
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $UserName = $_POST["UserName"];
    $Password = $_POST["Password"];

    // Example: Replace this with your actual authentication logic using a database query
    $sql = "SELECT * FROM usercustomer WHERE username = '$UserName' AND password = '$Password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Check if a matching user is found
        if (mysqli_num_rows($result) == 1) {
            // Authentication successful
            $userRow = mysqli_fetch_assoc($result);
            $_SESSION["UserName"] = $UserName;

            // Update logs table
            $signInTime = date("Y-m-d H:i:s");
            $activity = "Login";
            
            // Insert log record
            $insertLogQuery = "INSERT INTO logs (UserName, Activity, SignInTime) 
                               VALUES ('$UserName', '$Activity', '$signInTime')";
            mysqli_query($conn, $insertLogQuery);

            header("Location: dashboard.php");
            exit();
        } else {
            // Authentication failed
            $error_message = "Invalid username or password";
        }
    } else {
        // Database query failed
        $error_message = "Database query error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content here -->
</head>

<body>
 <div class="header">
        Direct Insurance Agency
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <img src="img/lucy.jpg" alt="Logo" class="logo">
                <h2>Direct Insurance - Client Side</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="UserName">Username:</label>
                        <input type="text" class="form-control" id="UserName" name="UserName" required>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password:</label>
                        <input type="password" class="form-control" id="Password" name="Password" required>
                    </div>
                    <button type="submit" class="btn btn-success">Login</button>
                </form>
                <div class="mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
                <?php
                // Display error message if authentication failed
                if (isset($error_message)) {
                    echo '<div style="color: red;">' . $error_message . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
 <div class="footer">
        &copy; <?php echo date("Y"); ?> Direct Insurance Agency
    </div>

</body>

<style>
.footer {
            background-color: blue;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        .header {
            background-color: blue;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }

    body {
    background-color: grey;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
    }

    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
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
        display: block;
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

</html>
