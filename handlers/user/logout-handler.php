<?php
session_start();

// Redirect to the login page if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login-form.php');
    exit();
}

// Clear session data and destroy the session
session_unset();
session_destroy();

// Start a new session and set a logout message
session_start();
$_SESSION["message"] = "Logout Successful";

// Redirect back to the login page
header('Location: ../../login-form.php');
exit();
?>
