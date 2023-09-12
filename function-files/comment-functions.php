<?php
function addComment($post_id, $comment)
{
    include "../includes/connection.php";
    session_start();
    $userId = $_SESSION["user_id"];

    $sql = "INSERT INTO comment(post_id, commenter_id, comment) VALUES(?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $userId, $comment);

    if ($stmt->execute()) {
        return "Commented";
    } else {
        return "Error adding comment";
    }
}

function delComment($comment_id)
{
    include "../includes/connection.php";

    $sql = "DELETE FROM comment WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        return "Uncommented";
    } else {
        return "Error deleting comment";
    }
}

function getCommentsForPost($post_id)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM comment WHERE post_id=? ORDER BY cdate DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);

    return $comments;
}

function getCommentCount($post_id)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM comment WHERE post_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['count'];
}

function getPostIdFromCommentId($commentId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM comment WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();

    $result = $stmt->get_result();
    $comment = $result->fetch_assoc();

    return $comment["comment"];
}

$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "addComment") {
        $response = addComment($_GET["postId"], $_GET["comment"]);
    }
    if ($_GET["action"] == "delComment") {
        $response = delComment($_GET["commentId"]);
    }
}
echo $response;
?>