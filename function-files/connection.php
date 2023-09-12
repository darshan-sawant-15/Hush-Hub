<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "socialmediaapp";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 character set
$conn->set_charset("utf8");
?>
