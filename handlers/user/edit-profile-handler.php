<?php
include "../../includes/connection.php";
include "../../function-files/user-functions.php";
session_start();


$fname = $_POST['fname'];
$fname = $_POST['fname'];
if (empty($fname)) {
    $_SESSION['errorMessage'] = "Full Name cannot be empty";
    header("Location: ../user/user-edit-profile.php");
    exit();
}
if (strlen($fname) > 255) {
    $_SESSION['errorMessage'] = "Full Name should be of maximum 255 characters";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}

$uname = $_POST['uname'];
$uname = $_POST['uname'];
if (empty($uname)) {
    $_SESSION['errorMessage'] = "Username cannot be empty";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
$unamePattern = '/^(?=.*[a-zA-Z])[a-zA-Z0-9_-]{4,30}$/';
if (!preg_match($unamePattern, $uname)) {
    $_SESSION['errorMessage'] = "Enter username according to specified format";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
if (checkIfUsernameExists($uname) && $uname != $_SESSION["username"]) {
    $_SESSION['errorMessage'] = "Username is already taken";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}

$age = $_POST['age'];
$age = $_POST['age'];
if (empty($age)) {
    $_SESSION['errorMessage'] = "Age cannot be empty";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
if ($age < 13) {
    $_SESSION['errorMessage'] = "You need to be minimum 13 years of age to create an account";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}


$email = $_POST['email'];
if (empty($email)) {
    $_SESSION['errorMessage'] = "Email cannot be empty";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
$emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailPattern, $email)) {
    $_SESSION['errorMessage'] = "Enter valid email address";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
if (checkIfEmailExists($email) && $email != $_SESSION["email"]) {
    $_SESSION['errorMessage'] = "Email has been already used";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}

$phone = $_POST['phone'];
if (empty($phone)) {
    $_SESSION['errorMessage'] = "Phone number cannot be empty";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
$phonePattern = '/^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$/';
if (!preg_match($phonePattern, $phone)) {
    $_SESSION['errorMessage'] = "Enter valid Indian phone number";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}
if (checkIfPhoneExists($phone) && $phone != $_SESSION["phone"]) {
    $_SESSION['errorMessage'] = "Phone Number has been already used";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}

$bio = $_POST['bio'];
if (strlen($bio) > 100) {
    $_SESSION['errorMessage'] = "Bio cannot be of more than 100 characters";
    header("Location: ../../user/user-edit-profile.php");
    exit();
}


$privacy = $_POST['privacy'];

$opassword = $_POST["cpassword"];
$npassword = $_POST["npassword"];
$ccpassword = $_POST['ccpassword'];
$hashPasswordFromSesh = $_SESSION["password"];

$finalPassword = "";
if ($ccpassword != $hashPasswordFromSesh) {
    if (!password_verify($opassword, $hashPasswordFromSesh)) {
        $_SESSION['errorMessage'] = "Incorrect current password";
        header("Location: ../../user/user-edit-profile.php");
        exit();
    }
    if (password_verify($npassword, $hashPasswordFromSesh)) {
        $_SESSION['errorMessage'] = "New password cannot be old password";
        header("Location: ../../user/user-edit-profile.php");
        exit();
    }
    if ($npassword != $ccpassword) {
        $_SESSION['errorMessage'] = "Passwords don't match";
        header("Location: ../../user/user-edit-profile.php");
        exit();
    }

    $finalPassword = password_hash($npassword, PASSWORD_DEFAULT);
} else {
    $finalPassword = $ccpassword;
}

// Get the uploaded image file
$image = $_FILES['image'];
$imageRemoved = $_POST["image-removed"];
if ($imageRemoved == 1) {
    $newImageName = "default.png";
} else {
    $newImageName = $_SESSION['profile_picture']; // Default value
}

if (!empty($image['name']) && $image['name'] != "") {
    // Validate the image file
    $uploadPath = '../../assets/images/uploads/profile-pictures';
    $imageName = $image['name'];
    $imageTempName = $image['tmp_name'];
    $newImageName = $uname . "-" . "profile.jpg";
    $imagePath = $uploadPath . "/" . $newImageName;
    $imageType = $image['type'];

    if (getimagesize($imageTempName) == false) {
        // Display an error message
        $_SESSION["errorMessage"] = 'Invalid file type. Only image files are allowed.';
        header("Location: ../../user/user-edit-profile.php");
        exit();
    }
    $maxFileSize = 5 * 1024 * 1024;
    if ($image['size'] > $maxFileSize) {
        $_SESSION["errorMessage"] = 'Max Image Size is 5MB.';
        header("Location: ../../user/user-edit-profile.php");
        exit();
    }

    // Move the uploaded image to the destination path
    move_uploaded_file($image['tmp_name'], $imagePath);
}

$userId = $_SESSION['user_id'];

$sql = "UPDATE user SET uname=?, fname=?, bio=?, profile_picture=?, privacy=?, phone=?, email=?, password=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", $uname, $fname, $bio, $newImageName, $privacy, $phone, $email, $finalPassword, $userId);

if ($stmt->execute()) {
    $_SESSION['successMessage'] = "Changes made successfully";
    $_SESSION['username'] = $uname;
    $_SESSION['fname'] = $fname;
    $_SESSION["bio"] = $bio;
    $_SESSION["age"] = $age;
    $_SESSION["email"] = $email;
    $_SESSION['profile_picture'] = $newImageName;
    $_SESSION["phone"] = $phone;
    $_SESSION["privacy"] = $privacy;
    $_SESSION["password"] = $finalPassword;
    $_SESSION['loggedIn'] = true;
} else {
    $_SESSION['errorMessage'] = "Something went wrong: " . $stmt->error;
}

// Redirect to a success page or any other appropriate action
header('Location: ../../user/user-profile.php');
exit();
?>