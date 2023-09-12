<?php
function like($post_id)
{
    include "../includes/connection.php";
    session_start();
    $userId = $_SESSION["user_id"];

    $sql = "INSERT INTO likes(post_id, liker_id) VALUES(?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $userId);

    if ($stmt->execute()) {
        $response = "Liked";
    } else {
        $response = "Error liking";
    }
    return $response;
}

function unlike($post_id)
{
    include "../includes/connection.php";
    session_start();
    $userId = $_SESSION["user_id"];

    $sql = "DELETE FROM likes WHERE post_id=? AND liker_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $userId);

    if ($stmt->execute()) {
        $response = "Unliked";
    } else {
        $response = "Error unliking";
    }
    return $response;
}

function hasLiked($post_id, $user_id)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM likes WHERE post_id = ? AND liker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return true;
    }

    return false;
}

function getLikeCount($post_id)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    return 0;
}

function getLikersIDs($id)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM likes WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row["liker_id"];
    }
    return $ids;
}

$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "like") {
        $response = like($_GET["postId"]);
    }
    if ($_GET["action"] == "unlike") {
        $response = unlike($_GET["postId"]);
    }
    if ($_GET["action"] == "getLikeCount") {
        $response = getLikeCount($_GET["postId"]);
    }
}
echo $response;


?>