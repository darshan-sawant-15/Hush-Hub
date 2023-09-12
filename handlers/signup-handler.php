<?php
include "../includes/connection.php";
include "../function-files/user-functions.php";
session_start();

//fname validation      
$fname = $_POST['fname'];
if (empty($fname)) {
    $_SESSION['errorMessage'] = "Full Name cannot be empty";
    header("Location: ../signup-form.php");
    exit();
}
if (strlen($fname) > 255) {
    $_SESSION['errorMessage'] = "Full Name should be of maximum 255 characters";
    header("Location: ../signup-form.php");
    exit();
}

//age validation
$age = $_POST['age'];
if (empty($age)) {
    $_SESSION['errorMessage'] = "Age cannot be empty";
    header("Location: ../signup-form.php");
    exit();
}
if ($age < 13) {
    $_SESSION['errorMessage'] = "You need to be minimum 13 years of age to create an account";
    header("Location: ../signup-form.php");
    exit();
}

//email validation
$email = $_POST['email'];
if (empty($email)) {
    $_SESSION['errorMessage'] = "Email cannot be empty";
    header("Location: ../signup-form.php");
    exit();
}
$emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailPattern, $email)) {
    $_SESSION['errorMessage'] = "Enter valid email address";
    header("Location: ../signup-form.php");
    exit();
}
if (checkIfEmailExists($email)) {
    $_SESSION['errorMessage'] = "Email has been already used";
    header("Location: ../signup-form.php");
    exit();
}

//phone validation
$phone = $_POST['phone'];
if (empty($phone)) {
    $_SESSION['errorMessage'] = "Phone number cannot be empty";
    header("Location: ../signup-form.php");
    exit();
}
$phonePattern = '/^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$/';
if (!preg_match($phonePattern, $phone)) {
    $_SESSION['errorMessage'] = "Enter valid Indian phone number";
    header("Location: ../signup-form.php");
    exit();
}
if (checkIfPhoneExists($phone)) {
    $_SESSION['errorMessage'] = "Phone Number has been already used";
    header("Location: ../signup-form.php");
    exit();
}

//username validation
$uname = $_POST['uname'];
if (empty($uname)) {
    $_SESSION['errorMessage'] = "Username cannot be empty";
    header("Location: ../signup-form.php");
    exit();
}
$unamePattern = '/^(?=.*[a-zA-Z])[a-zA-Z0-9_-]{4,30}$/';
if (!preg_match($unamePattern, $uname)) {
    $_SESSION['errorMessage'] = "Enter username according to specified format";
    header("Location: ../signup-form.php");
    exit();
}
if (checkIfUsernameExists($uname)) {
    $_SESSION['errorMessage'] = "Username is already taken";
    header("Location: ../signup-form.php");
    exit();
}

$password = $_POST['password'];
if (empty($password)) {
    $_SESSION['errorMessage'] = "Password cannot be empty";
    header("Location: ../signup-form.php");
    exit();
}
$passwordPattern = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/';
if (!preg_match($passwordPattern, $password)) {
    $_SESSION['errorMessage'] = "Enter password according to specified format";
    header("Location: ../signup-form.php");
    exit();
}

$cpassword = $_POST['cpassword'];
if (empty($cpassword)) {
    $_SESSION['errorMessage'] = "Re-enter password to confirm";
    header("Location: ../signup-form.php");
    exit();
}
if($password!=$cpassword){
    $_SESSION['errorMessage'] = "Passwords don't match";
    header("Location: ../signup-form.php");
    exit();
}


// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement using prepared statements
$stmt = $conn->prepare("INSERT INTO user(uname, fname, phone, email, password, age, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");
$stmt->bind_param("sssssi", $uname, $fname, $phone, $email, $hashedPassword, $age);

if ($stmt->execute()) {
    $_SESSION['successMessage'] = "Registration successful!";
} else {
    $_SESSION['errorMessage'] = "Something went wrong";
}

// Close the statement and connection
$stmt->close();
$conn->close();

header("Location: ../signup-form.php");
exit();
?>