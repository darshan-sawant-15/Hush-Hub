<?php

function reportPost($postId, $reporterId, $posterId)
{
    include "../includes/connection.php";
    include "notification-functions.php";
    include "post-functions.php";

    $sql = "INSERT INTO reported_posts(post_id, reporter_id, poster_id) VALUES('$postId', '$reporterId', '$posterId');";

    if ($conn->query($sql) === TRUE) {
        $response = "Post Reported";
        addNotification($posterId, "Your post has been reported by an user. You may be banned permenantly from the platform, if your post is found to be violating community guidelines laid out for the platform <a href=viewPost.php?id=" . $postId . ">(View Reported Content)</a>");
        addNotification($reporterId, "We are currently reviewing the post that you reported. We will keep you updated about further actions that will be taken in regards to the post, your identity will be kept hidden from the creator of this post <a href=viewPost.php?id=" . $postId . ">(View Reported Content)</a>");
    } else {
        $response = "Something went wrong";
    }



    return $response;
}

function reportComment($commentId, $reporterId, $commenterId)
{
    include "../includes/connection.php";
    include "notification-functions.php";
    include "comment-functions.php";
    include "post-functions.php";

    $sql = "INSERT INTO reported_comments(comment_id, reporter_id, commenter_id) VALUES('$commentId', '$reporterId', '$commenterId');";

    if ($conn->query($sql) === TRUE) {
        $response = "Comment Reported";
    } else {
        $response = "Something went wrong";
    }

    $postId = getPostIdFromCommentId($commentId);
    $postCaption = getCaptionFromPostId($postId);
    addNotification($commenterId, "Your comment on the a post has been reported by an user. You may be banned permenantly from the platform, if your comment is found to be violating community guidelines laid out for the platform <a href=viewPost.php?id=" . $postId . ">(View Reported Content)</a>");
    addNotification($reporterId, "We are currently reviewing the comment that you reported. We will keep you updated about further actions that will be taken in regards to the comment, your identity will be kept hidden from the user who posted the comment <a href=viewPost.php?id=" . $postId . ">(View Reported Content)</a>");

    return $response;
}

function hasReportedPost($userId, $postId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM reported_posts WHERE reporter_id = '$userId' AND post_id = '$postId'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        return true;
    }

    return false;
}

function hasReportedComment($userId, $commentId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM reported_comments WHERE reporter_id = '$userId' AND comment_id = '$commentId'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        return true;
    }

    return false;
}


$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "reportPost") {
        $response = reportPost($_GET["postId"], $_GET["reporterId"], $_GET["posterId"]);
    }
    if ($_GET["action"] == "reportComment") {
        $response = reportComment($_GET["commentId"], $_GET["reporterId"], $_GET["commenterId"]);
    }
}
echo $response;

?>