<?php
session_name("client_session");
session_start();

// Check if the user is logged in (session variable is set)
if (isset($_SESSION["UserName"])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Redirect to a login page or another page
    header("Location: index.php");
    exit(); // Ensure that no further code is executed after redirection
}
?>
