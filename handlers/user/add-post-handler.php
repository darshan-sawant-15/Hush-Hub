<?php
include "../../includes/connection.php";
include "../../function-files/post-functions.php";
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted caption
    $caption = $_POST['caption'];
    if (strlen($caption) > 280) {
        $_SESSION["errorMessage"] = 'Post caption cannot be more than 280 characters';
        header('Location: ../../user/user-post-form.php');
        exit();
    }

    $likeCount = $_POST['like-chk'];
    $allowComments = $_POST['comm-chk'];

    // Get the uploaded image file
    $image = $_FILES['image'];

    // Validate the uploaded image
    if (!empty($image) && getimagesize($image['tmp_name'])) {
        // Generate a unique image name
        $newImageName = $_SESSION["username"] . "-post" . (getPostCount($_SESSION["user_id"]) + 1) . ".jpg";
        $imagePath = '../../assets/images/uploads/posts/' . $newImageName;

        $maxFileSize = 5 * 1024 * 1024;
        if ($image['size'] > $maxFileSize) {
            $_SESSION["errorMessage"] = 'Max Image Size is 5MB.';
            header('Location: ../../user/user-post-form.php');
            exit();
        }

        // Move the uploaded image to the destination path
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $userId = $_SESSION['user_id'];

            // Prepare and execute the SQL query
            $sql = "INSERT INTO post(user_id, caption, image_name, show_like_count, allow_comments) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $userId, $caption, $newImageName, $likeCount, $allowComments);

            if ($stmt->execute()) {
                $_SESSION['successMessage'] = "Your Post is now online";
            } else {
                $_SESSION['errorMessage'] = "Something went wrong";
            }
        } else {
            $_SESSION["errorMessage"] = "Failed to move uploaded image";
        }
    } else {
        $_SESSION["errorMessage"] = "Invalid file type. Only image files are allowed.";
    }

    // Redirect to a success page or any other appropriate action
    header('Location: ../../user/user-post-form.php');
    exit();
}
?>