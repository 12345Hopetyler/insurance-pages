<?php
include "database/connection.php";
session_name("client_session");
session_start();
$loggedInUser = isset($_SESSION["UserName"]) ? $_SESSION["UserName"] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            max-width: 500px;
            margin: auto;
        }

        .message-bubble {
            position: relative;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .delete-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            color: red;
            cursor: pointer;
        }

        .sender {
            background-color: #007bff;
            color: #fff;
            text-align: right;
        }

        .receiver {
            background-color: #f0f0f0;
            color: #333;
            text-align: left;
        }
    </style>
</head>
<body>
 

    <?php include "sidebar.php"; ?>
    <div class="container mt-5 chat-container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Chat
            </div>
            <div class="card-body chat-box" id="chatBox">
                <?php
// Assuming $loggedInUser is set to the current user's username

// Prepare SQL query to select messages where the current user is either sender or receiver
$sql = "SELECT * FROM messages WHERE sender = ? OR receiver = ? ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $loggedInUser, $loggedInUser);
$stmt->execute();
$result = $stmt->get_result();

// Check if messages were found
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Extract message details from the fetched row
        $messageId = $row["id"];
        $message = $row["message"];
        $sender = $row["sender"];
        $receiver = $row["receiver"];
        $time = $row["time"];

        // Determine the CSS class for message bubble based on the role of the current user in the message
        if ($sender === $loggedInUser) {
            // Current user is the sender
            $bubbleClass = 'sender';
        } else {
            // Current user is the receiver
            $bubbleClass = 'receiver';
        }

        // Output the message bubble HTML
        echo "<div class='message-bubble $bubbleClass'>
                <span>$message</span><br>
                <small>$sender - $time</small>
                <form method='post' action='' style='display: inline;'>
                    <input type='hidden' name='message_id' value='$messageId'>
                    <button type='submit' class='btn btn-link btn-sm' onclick='return confirm(\"Are you sure you want to delete this message?\");'>
                        <i class='fas fa-trash delete-icon'></i>
                    </button>
                </form>
            </div>";
    }
} else {
    echo "<p>No messages yet.</p>";
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>


            </div>

            <div class="card-footer">
                <form id="messageForm" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" id="messageInput" name="message" placeholder="Type your message...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" name="submit" type="submit">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for sending and deleting messages -->
    <script src="chat.js">
    </script>
    <script>$('#messageForm').submit(function(event) {
    event.preventDefault(); // Prevent default form submission
    sendMessage(); // Call sendMessage function to send the message
});

function sendMessage() {
    var message = $("#messageInput").val().trim(); // Get the message input value
    if (message !== "") {
        var currentTime = new Date().toLocaleTimeString(); // Get current time
        var sender = "<?php echo isset($_SESSION['UserName']) ? $_SESSION['UserName'] : 'Sender'; ?>"; // Get sender name from session

        // Send message data to server using AJAX
        $.ajax({
            type: "POST",
            url: "insert_message.php", // URL to PHP file handling insertion
            data: { sender: sender, message: message },
            success: function(response) {
                console.log("Message sent successfully");
                // Append sent message to chat box dynamically
                var senderMessage = "<div class='message-bubble sender'><span>" + message + "</span><br><small>" + currentTime + "</small></div>";
                $("#chatBox").append(senderMessage);
                $("#messageInput").val(""); // Clear input field after sending message
            },
            error: function(xhr, status, error) {
                console.error("Error sending message:", error);
            }
        });
    }
}
</script>
</body>
</html>
