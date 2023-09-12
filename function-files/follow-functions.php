<?php 
function follow($followerId, $followingId)
{
    include "../includes/connection.php";
    include "notification-functions.php";
    include "user-functions.php";

    $sql = "INSERT INTO follower(follower_id, following_id, accepted) VALUES(?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followingId);

    if ($stmt->execute()) {
        $response = "Unfollow";
    } else {
        $response = "Error following";
    }
    addNotification($followingId, "You are now followed by", $followerId);

    return $response;
}

function followPrivate($followerId, $followingId)
{
    include "../includes/connection.php";
    $sql = "INSERT INTO follower(follower_id, following_id, accepted) VALUES(?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followingId);

    if ($stmt->execute()) {
        return "Request Sent";
    } else {
        return "Error sending request";
    }
}

function unfollow($followerId, $followingId)
{
    include "../includes/connection.php";
    include "notification-functions.php";
    include "user-functions.php";

    $sql = "DELETE FROM follower WHERE follower_id=? AND following_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followingId);

    if ($stmt->execute()) {
        $response = "Follow";
    } else {
        $response = "Error unfollowing";
    }

    $uname = getUserNameFromId($followerId);
    deleteNotification($followingId, $followerId);

    return $response;
}

function acceptRequest($followerId, $followingId)
{
    include "../includes/connection.php";
    include "notification-functions.php";
    include "user-functions.php";

    $sql = "UPDATE follower SET accepted=1 WHERE follower_id=? AND following_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followingId);

    if ($stmt->execute()) {
        $response = "Follow";
    } else {
        $response = "Error accepting request";
    }

    $uname = getUserNameFromId($followerId);
    addNotification($followingId, "You are now followed by", $followerId);

    return $response;
}

function doesFollow($followerId, $followingId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM follower WHERE follower_id = ? AND following_id = ? AND accepted = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return true;
    }

    return false;
}

function hasSentRequest($followerId, $followingId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM follower WHERE follower_id = ? AND following_id = ? AND accepted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return true;
    }

    return false;
}

function getFollowersCount($id)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM follower WHERE following_id = ? AND accepted = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}

function getFollowingCount($id)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM follower WHERE follower_id = ? AND accepted = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}

function getRequestCount($id)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM follower WHERE following_id = ? AND accepted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}

function getFollowersIDs($id)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM follower WHERE following_id = ? AND accepted = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row["follower_id"];
    }
    return $ids;
}

function getFollowingIDs($id)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM follower WHERE follower_id = ? AND accepted = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row["following_id"];
    }
    return $ids;
}

function getRequestorIDs($id)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM follower WHERE following_id = ? AND accepted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row["follower_id"];
    }
    return $ids;
}

function getBothWaysFollow($id)
{
    $followerIds = getFollowersIDs($id);
    $bWIds = [];
    foreach ($followerIds as $followerId) {
        if (doesFollow($id, $followerId)) {
            $bWIds[] = $followerId;
        }
    }
    return $bWIds;
}


$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "getFollowersCount") {
        $response = getFollowersCount($_GET["id"]);
    }

    if ($_GET["action"] == "getFollowingCount") {
        $response = getFollowingCount($_GET["id"]);
    }

    if ($_GET["action"] == "follow") {
        $response = follow($_GET["followerId"], $_GET["followingId"]);
    }

    if ($_GET["action"] == "unfollow") {
        $response = unfollow($_GET["followerId"], $_GET["followingId"]);
    }

    if ($_GET["action"] == "followPrivate") {
        $response = followPrivate($_GET["followerId"], $_GET["followingId"]);
    }

    if ($_GET["action"] == "acceptRequest") {
        $response = acceptRequest($_GET["followerId"], $_GET["followingId"]);
    }
    if ($_GET["action"] == "getRequestCount") {
        $response = getRequestCount($_GET["id"]);
    }

}
echo $response;
?>