<?php
include "../includes/connection.php";
session_start();

$uname = $_POST['uname'];
$password = $_POST['password'];

// Check if it's the admin
if ($uname === "admin" && $password === "Admin@123") {
    $_SESSION["role"] = "admin";
    $_SESSION["username"] = "admin";
    header("Location: ../adminHome.php");
    exit();
}

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM user WHERE uname = ?");
$stmt->bind_param("s", $uname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $row["password"])) {
        // Store necessary user details in session
        $_SESSION['user_id'] = $row["id"];
        $_SESSION['username'] = $row["uname"];
        $_SESSION['fname'] = $row["fname"];
        $_SESSION['bio'] = $row["bio"];
        $_SESSION['age'] = $row["age"];
        $_SESSION['email'] = $row["email"];
        $_SESSION['phone'] = $row["phone"];
        $_SESSION['profile_picture'] = $row["profile_picture"];
        $_SESSION['privacy'] = $row["privacy"];
        $_SESSION['password'] = $row["password"];
        $_SESSION['role'] = $row["role"];
        $_SESSION['loggedIn'] = true;

        // Unset the prepared statement and close the connection
        $stmt->close();
        $conn->close();

        header("Location: ../user/user-home.php"); // Redirect to the dashboard page or any other authenticated page
        exit();
    }
}

// Unset the prepared statement and close the connection
$stmt->close();
$conn->close();

$_SESSION["errorMessage"] = "Invalid username or password";
header("Location: ../login-form.php");
exit();
?>