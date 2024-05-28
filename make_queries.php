<?php
include "database/connection.php";
session_name("client_session");
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled Chatbot</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .chatbot-container {
            width: 100%;
            height: 100vh; /* Full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5; /* Light gray background */
        }

        .chatbot-frame {
            width: 90%; /* Adjust the width as needed */
            max-width: 800px; /* Maximum width for the chatbot frame */
            height: 80vh; /* Adjust the height as needed */
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden; /* Hide any overflow content */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
        }
    </style>
</head>
<body>
     <?php include "sidebar.php";?>
    <div class="chatbot-container">
        <iframe
            src="https://www.chatbase.co/chatbot-iframe/FHzFUecdRfsXvI7M-gVuO"
            title="Chatbot"
            class="chatbot-frame"
            frameborder="0"
            allowfullscreen
        ></iframe>
    </div>
</body>
</html>
