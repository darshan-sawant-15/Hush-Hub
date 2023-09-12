<?php
include "../../includes/connection.php";
include "../../function-files/post-functions.php";
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted values
    $postId = $_POST["postId"];
    $caption = $_POST['caption'];
    if (strlen($caption) > 280) {
        $_SESSION["errorMessage"] = 'Post caption cannot be more than 280 characters';
        header('Location: ../../user/user-edit-post.php');
        exit();
    }
    $likeCount = $_POST['like-chk'];
    $allowComments = $_POST['comm-chk'];

    // Prepare and execute the SQL query
    $sql = "UPDATE post SET caption=?, show_like_count=?, allow_comments=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $caption, $likeCount, $allowComments, $postId);

    if ($stmt->execute()) {
        $_SESSION['successMessage'] = "Changes made successfully";
    } else {
        $_SESSION['errorMessage'] = "Something went wrong: " . $conn->error;
    }

    // Redirect to a success page or any other appropriate action
    header('Location: ../../user/user-view-post.php?id=' . $postId);
    exit();
}
?>