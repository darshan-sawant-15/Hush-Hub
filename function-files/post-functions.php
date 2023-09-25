<?php

function deletePost($postId)
{
    include "connection.php";
    session_start();

    $post = getPostFromId($postId);

    //checking if valid user is deleting the post
    if (!isset($_SESSION["user_id"]) || ($_SESSION["user_id"] != $post["user_id"])) {
        $response = "You don't have permissions to delete this post";
        return $response;
    }

    $imagePath = "../assets/images/uploads/posts/" . $post["image_name"];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $sql = "DELETE FROM post WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        $_SESSION['successMessage'] = "Your post is now deleted";
        $response = "Post Deleted";
    } else {
        $_SESSION['errorMessage'] = "Something went wrong";
        $response = "Something went wrong";
    }

    $stmt->close();
    return $response;
}

function getCaptionFromPostId($postId)
{
    include "connection.php";

    $sql = "SELECT caption FROM post WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    $stmt->bind_result($caption);

    $stmt->fetch(); // Fetch the caption value from the database

    $stmt->close();
    return $caption; // Return the caption value
}

function getUserIdFromPostId($postId)
{
    include "connection.php";

    $sql = "SELECT user_id FROM post WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    $stmt->bind_result($userId);

    $stmt->fetch(); // Fetch the caption value from the database

    $stmt->close();
    return $userId; // Return the caption value
}

function getPosts($id)
{
    include "connection.php";

    $sql = "SELECT * FROM post WHERE user_id = ? ORDER BY pdate DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function getPostCount($id)
{
    include "connection.php";

    $sql = "SELECT COUNT(*) as count FROM post WHERE user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    $stmt->close();
    return 0;
}

function getPostFromId($postId)
{
    include "connection.php";

    $sql = "SELECT * FROM post WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

function getPostsForFeed()
{
    include "connection.php";
    include "report-functions.php";

    $userId = $_SESSION["user_id"];

    $sql = "SELECT * FROM post ORDER BY pdate DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];

    if ($result->num_rows > 0) {
        while ($post = $result->fetch_assoc()) {
            if (doesFollow($userId, $post["user_id"])) {
                // !hasReportedPost($userId, $post["id"])) {
                $posts[] = $post;
            }
        }
    }

    $stmt->close();

    return $posts;
}

function givePostUI($postId)
{
    include "user-functions.php";
    include "follow-functions.php";
    include "like-functions.php";
    include "date-functions.php";
    include "comment-functions.php";
    include "report-functions.php";
    session_start();
    $post = getPostFromId($postId);

    $html = '';
    if (!empty($post)) {
        if (!isPrivate($post["user_id"]) || (isPrivate($post["user_id"]) && doesFollow($_SESSION["user_id"], $post["user_id"])) || ($_SESSION["user_id"] == $post["user_id"])) {

            $html .= '<div class="card mb-4" id="' . $post["id"] . '">';
            $html .= '<div class="card-body">';
            $html .= '<div class="post-header d-flex align-items-center">';
            $html .= '<img src="../assets/images/uploads/profile-pictures/' . getUserImageFromId($post["user_id"]) . '" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px; border: 1px solid #ccc; margin-right: 10px;">';
            $html .= '<h6>';
            if ($post["user_id"] == $_SESSION["user_id"]) {
                $html .= '<a href="../user/user-profile.php">';
                $html .= "@" . getUserNameFromId($post["user_id"]);
                $html .= '</a>';
            } else {
                $html .= '<a href="../user/user-view-profile.php?id=' . $post["user_id"] . '">';
                $html .= "@" . getUserNameFromId($post["user_id"]);
                $html .= '</a>';
            }
            $html .= '<br><small>' . getDateForPost($post["pdate"]) . '</small>';
            $html .= '</h6>';
            if ($post["user_id"] == $_SESSION["user_id"]) {
                $html .= '<button class="btn btn-sm btn-success ml-auto" onclick="window.location.href=\'../user/user-edit-post-form.php?id=' . $post["id"] . '\'">Edit</button>';
                $html .= '<button class="btn btn-sm btn-danger ml-2" onclick="deletePost(' . $post["id"] . ');">Delete</button>';
            }
            // else {
            //     if (!hasReportedPost($_SESSION["user_id"], $post["id"])) {
            //         $html .= '<button class="btn btn-sm btn-danger ml-auto"  onclick="reportPost(' . $post["id"] . ',' . $post["user_id"] . ',' . $_SESSION["user_id"] . ')">Report</button>';
            //     } else {
            //         $html .= '<button class="btn btn-sm btn-danger ml-auto" onclick="alert(\'This post is under review\');">Reported</button>';
            //     }
            // }
            $html .= '</div>';
            $html .= '<div class="post-content mt-2 mb-2">';
            $html .= '<p>';
            $html .= $post["caption"];
            $html .= '</p>';
            $html .= '</div>';
            $html .= '<div class="text-center">';
            $html .= '<img src="../assets/images/uploads/posts/' . $post["image_name"] . '" alt="Post Image" class="card-img-top" style="border: 1px solid #2b7a78; border-radius: 2px;">';
            $html .= '</div>';
            $html .= '<div class="post-actions mt-3">';
            $html .= '<div class="btn-group d-flex align-items-center">';
            $html .= '<div style="display:none" id="post-id">' . $post["id"] . '</div>';
            // if (!hasReportedPost($_SESSION["user_id"], $post["id"])) {
            if (!hasLiked($post["id"], $_SESSION["user_id"])) {
                $html .= '<button class="btn btn-sm col-md-4 mr-2" id="like-btn" style="background-color: #ffbfb8; color:#17252A; border: 1px solid #B36F75; border-radius: 2px" ><i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;" onclick="like(' . $post["id"] . ');"></i>';
                if ($post["show_like_count"] == 1 || $post["user_id"] == $_SESSION["user_id"]) {
                    $html .= '<span id="like-count"';
                    if (getLikeCount($post["id"]) > 0) {
                        $html .= 'onclick="showLikers(' . $post["id"] . ')"';
                    }
                    $html .= '>' . getLikeCount($post["id"]) . '</span>';
                }
                $html .= '</button>';
            } else {
                $html .= '<button class="btn btn-sm col-md-4 mr-2" id="like-btn" style="background-color: #ffbfb8; color:#17252A; border: 1px solid #B36F75; border-radius: 2px" ><i class="fa-sharp fa-solid fa-heart" style="color: red; margin-right:10px;" onclick="unlike(' . $post["id"] . ');"></i>';
                if ($post["show_like_count"] == 1 || $post["user_id"] == $_SESSION["user_id"]) {
                    $html .= '<span id="like-count"';
                    if (getLikeCount($post["id"]) > 0) {
                        $html .= 'onclick="showLikers(' . $post["id"] . ')"';
                    }
                    $html .= '>' . getLikeCount($post["id"]) . '</span>';
                }
                $html .= '</button>';
            }
            $html .= '<button class="btn btn-sm col-md-4 mr-2" style="background-color: white; border: 1px solid #2B7A78; border-radius: 2px" id="comment-btn" onclick="newComment(this);"';
            if ($post["allow_comments"] == 0) {
                $html .= 'disabled';
            }
            $html .= '><i class="fa-regular fa-comment" style="color: #2b7a78;margin-right:10px"></i>';
            if ($post["allow_comments"] == 1) {
                $html .= getCommentCount($post["id"]);
            }
            $html .= '</button>';
            $html .= '<button class="btn btn-sm col-md-4" style="background-color: white; border: 1px solid #2B7A78; border-radius: 2px"  onclick="shareContent();"><i class="fa-regular fa-share-from-square" style="color: #2b7a78;"></i></button>';
            $html .= '</div>';
            $html .= '</div>';

            if ($post["allow_comments"] == 0) {
                $html .= '<div class="comment-section mt-3" id="comment-section" style="display: block;">';
                if ($post["user_id"] == $_SESSION["user_id"]) {
                    $html .= '<h6>Comments are disabled by you</h6>';
                } else {
                    $html .= '<h6>Comments are disabled by the user</h6>';
                }

                $html .= '</div>';
            } else {
                $html .= '<div class="comment-section mt-3" id="comment-section" style="display: block;">';
                $html .= '<h6>Comments:</h6><hr>';

                $comments = getCommentsForPost($post["id"]);
                if (empty($comments)) {
                    $html .= '<div class="comments">';
                    $html .= '<div class="comment">';
                    $html .= 'No Comments Yet';
                    $html .= '</div>';
                    $html .= '</div>';

                } else {
                    foreach ($comments as $comment) {
                        $html .= '<div class="comments">';
                        $html .= '<div class="comment">';
                        $username = getUserNameFromId($comment["commenter_id"]);
                        $html .= '<a href="../user/user-view-profile.php?id=' . $comment["commenter_id"] . '"><strong>@' . $username . ': </strong></a>';
                        $html .= $comment["comment"];
                        if ($comment["commenter_id"] == $_SESSION["user_id"] || getUserIdFromPostId($comment["post_id"]) == $_SESSION["user_id"]) {
                            $html .= '<button class="btn btn-sm btn-danger btn-pill" onclick="delComment(' . $post["id"] . ',' . $comment["id"] . ');" style="margin-left:5px;padding:2px">Delete</button>';
                        }
                        //  else {
                        //     $html .= '<button class="btn btn-sm btn-danger btn-pill" onclick="reportComment(' . $comment["id"] . ',' . $comment["commenter_id"] . ',' . $_SESSION["user_id"] . ');" style="margin-left:5px;padding:2px">Report</button>';
                        // }
                        $html .= '</div>';
                    }
                }

                $html .= '<form class="comment-form mt-3" id="comment-form" style="display:none">';
                $html .= '<div class="form-group">';
                $html .= '<textarea class="form-control" rows="2" placeholder="Add a comment" id="new-comment" maxlength="280"></textarea>';
                $html .= '</div>';
                $html .= '<button onclick="event.preventDefault(); addComment(' . $post["id"] . ');" class="btn btn-sm" style="background-color: #17252A; color:#FEFFFF">Comment</button>';
                $html .= '</form>';
                $html .= '</div>';
            }
            // }

            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="card mt-2">';
            $html .= '<h5 class="text-center mt-4" style="padding-bottom:15px">Follow <a href="../user/user-view-profile.php?id=' . $post["user_id"] . '">';
            $html .= "@" . getUserNameFromId($post["user_id"]);
            $html .= '</a> to view this post</h5>';
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="card mt-2">';
        $html .= '<h5 class="text-center mt-4" style="padding-bottom:15px">No such post</h5>';
        $html .= '</div>';
    }

    return $html;
}

function giveCommentSection($postId)
{
    include "user-functions.php";
    include "follow-functions.php";
    include "like-functions.php";
    include "date-functions.php";
    include "comment-functions.php";
    session_start();
    $post = getPostFromId($postId);
    $html = '';

    if (!empty($post)) {
        if ($post["allow_comments"] == 0) {
            $html .= '<div class="comment-section mt-3" id="comment-section" style="display: block;">';
            $html .= '<h6>Comments are disabled by the user</h6>';
            $html .= '</div>';
        } else {
            $html .= '<div class="comment-section mt-3" id="comment-section" style="display: block;">';
            $html .= '<h6>Comments:</h6><hr>';

            $comments = getCommentsForPost($post["id"]);
            if (empty($comments)) {
                $html .= '<div class="comments">';
                $html .= '<div class="comment">';
                $html .= 'No Comments Yet';
                $html .= '</div>';
                $html .= '</div>';

            } else {
                foreach ($comments as $comment) {
                    $html .= '<div class="comments">';
                    $html .= '<div class="comment">';
                    $username = getUserNameFromId($comment["commenter_id"]);
                    $html .= '<a href="../user/user-view-profile.php?id=' . $comment["commenter_id"] . '"><strong>@' . $username . ': </strong></a>';
                    $html .= $comment["comment"];
                    if ($comment["commenter_id"] == $_SESSION["user_id"] || getUserIdFromPostId($comment["post_id"]) == $_SESSION["user_id"]) {
                        $html .= '<button class="btn btn-sm btn-danger btn-pill" onclick="delComment(' . $post["id"] . ',' . $comment["id"] . ');" style="margin-left:5px;padding:2px">Delete</button>';
                    }
                    // } else {
                    //     if (!hasReportedComment($_SESSION["user_id"], $comment["id"])) {
                    //         $html .= '<button class="btn btn-sm btn-danger btn-pill" onclick="reportComment(' . $comment["id"] . ',' . $comment["commenter_id"] . ',' . $_SESSION["user_id"] . ');" style="margin-left:5px;padding:2px">Report</button>';
                    //     } else {
                    //         $html .= '<button class="btn btn-sm btn-danger btn-pill" onclick="reportComment(' . $comment["id"] . ',' . $comment["commenter_id"] . ',' . $_SESSION["user_id"] . ');" style="margin-left:5px;padding:2px">Reported</button>';
                    //     }

                    // }
                    $html .= '</div>';
                }

            }

            $html .= '<form class="comment-form mt-3" id="comment-form" style="display:none">';
            $html .= '<div class="form-group">';
            $html .= '<textarea class="form-control" rows="2" placeholder="Add a comment" id="new-comment" maxlength="280"></textarea>';
            $html .= '</div>';
            $html .= '<button onclick="event.preventDefault(); addComment(' . $post["id"] . ');" class="btn btn-sm" style="background-color: #17252A; color:#FEFFFF">Comment</button>';
            $html .= '</form>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';
    }

    return $html;

}

function givePostList()
{
    include "user-functions.php";
    include "follow-functions.php";
    include "like-functions.php";
    include "date-functions.php";
    include "comment-functions.php";
    session_start();
    $posts = getPostsForFeed();

    $html = '';

    if (!empty($posts)) {
        foreach ($posts as $post) {
            $html .= '<div class="card mb-4" id="' . $post["id"] . '">';
            $html .= '<div class="card-body">';
            // onclick=\'window.location.href="viewPost.php?id=' . $post["id"] . '"\'>';
            $html .= '<div onclick=\'window.location.href="../user/user-view-post.php?id=' . $post["id"] . '"\'>';
            $html .= '<div class="post-header d-flex align-items-center">';
            $html .= '<img src="../assets/images/uploads/profile-pictures/' . getUserImageFromId($post["user_id"]) . '" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px; border: 1px solid #ccc; margin-right: 10px;">';
            $html .= '<h6>';
            $html .= '<a href="../user/user-view-profile.php?id=' . $post["user_id"] . '">';
            $html .= "@" . getUserNameFromId($post["user_id"]);
            $html .= '</a>';
            $html .= '<br><small>' . getDateForPost($post["pdate"]) . '</small>';
            $html .= '</h6>';
            $html .= '</div>';
            $html .= '<div class="post-content mt-2 mb-2">';
            $html .= '<p>';
            $html .= $post["caption"];
            $html .= '</p>';
            $html .= '</div>';
            $html .= '<div class="text-center">';
            $html .= '<img src="../assets/images/uploads/posts/' . $post["image_name"] . '" alt="Post Image" class="card-img-top" style="border: 1px solid #2b7a78; border-radius: 2px;">';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="post-actions mt-3">';
            $html .= '<div class="btn-group d-flex align-items-center">';
            $html .= '<div style="display:none" id="post-id">' . $post["id"] . '</div>';
            if (!hasLiked($post["id"], $_SESSION["user_id"])) {
                $html .= '<button class="btn btn-sm col-md-4 mr-2 no-click" id="like-btn" style="background-color: #ffbfb8; color:#17252A; border: 1px solid #B36F75; border-radius: 2px" ><i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;" onclick="like(' . $post["id"] . ');"></i>';
                if ($post["show_like_count"] == 1 || $post["user_id"] == $_SESSION["user_id"]) {
                    $html .= '<span id="like-count"';
                    if (getLikeCount($post["id"]) > 0) {
                        $html .= 'onclick="showLikers(' . $post["id"] . ')"';
                    }
                    $html .= '>' . getLikeCount($post["id"]) . '</span>';
                }
                $html .= '</button>';
            } else {
                $html .= '<button class="btn btn-sm col-md-4 mr-2 no-click" id="like-btn" style="background-color: #ffbfb8; color:#17252A; border: 1px solid #B36F75; border-radius: 2px" ><i class="fa-sharp fa-solid fa-heart" style="color: red; margin-right:10px;" onclick="unlike(' . $post["id"] . ');"></i>';
                if ($post["show_like_count"] == 1 || $post["user_id"] == $_SESSION["user_id"]) {
                    $html .= '<span id="like-count"';
                    if (getLikeCount($post["id"]) > 0) {
                        $html .= 'onclick="showLikers(' . $post["id"] . ')"';
                    }
                    $html .= '>' . getLikeCount($post["id"]) . '</span>';
                }
                $html .= '</button>';
            }
            $html .= '<button class="btn btn-sm col-md-4 mr-2 no-click" style="background-color: white; border: 1px solid #2B7A78; border-radius: 2px" onclick=\'window.location.href="../user/user-view-post.php?id=' . $post["id"] . '"\'';
            if ($post["allow_comments"] == 0) {
                $html .= 'disabled';
            }
            $html .= '><i class="fa-regular fa-comment no-click" style="color: #2b7a78; margin-right:10px"></i>';
            if ($post["allow_comments"] == 1) {
                $html .= getCommentCount($post["id"]);
            }
            $html .= '</button>';
            $html .= '<button class="btn btn-sm col-md-4 no-click" style="background-color: white; border: 1px solid #2B7A78; border-radius: 2px"  onclick="shareContent();"><i class="fa-regular fa-share-from-square" style="color: #2b7a78;"></i></button>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

    } else {
        $html .= '<div class="card">';
        $html .= '<h5 class="text-center mt-4" style="padding-bottom:15px">Nothing to See Here, Follow People To See Posts</h5>';
        $first = false;
        $html .= '</div>';
    }

    return $html;
}

$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "givePostList") {
        $response = givePostList();
    }

    if ($_GET["action"] == "givePostUI") {
        $response = givePostUI($_GET["postId"]);
    }

    if ($_GET["action"] == "delPost") {
        $response = deletePost($_GET["postId"]);
    }
    if ($_GET["action"] == "editPost") {
        $response = editPost($_GET["postId"]);
    }
    if ($_GET["action"] == "giveCommentSection") {
        $response = giveCommentSection($_GET["postId"]);
    }
}
echo $response;
