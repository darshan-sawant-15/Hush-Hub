<?php

function addNotification($user_id, $text, $about_id)
{
    include "../includes/connection.php";

    $stmt = $conn->prepare("INSERT INTO notification(user_id, text, about_id, seen) VALUES(?,?,?, 0);");
    $stmt->bind_param("iss", $user_id, $text, $about_id);

    if ($stmt->execute()) {
        return "Notification added";
    } else {
        return "Something went wrong: " . $stmt->error;
    }
}

function deleteNotification($userId, $aboutId)
{
    include "../includes/connection.php";

    $stmt = $conn->prepare("DELETE FROM notification WHERE user_id=? AND about_id=?");
    $stmt->bind_param("ss", $userId, $aboutId);

    if ($stmt->execute()) {
        return "Notification deleted";
    } else {
        return "Something went wrong: " . $stmt->error;
    }
}

function getNotificationCount($id)
{
    include "../includes/connection.php";

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notification WHERE user_id=? AND seen=0");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

function getNotifications($user_id)
{
    include "../includes/connection.php";

    $stmt = $conn->prepare("SELECT * FROM notification WHERE user_id=? ORDER BY timestamp DESC");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function markAsSeen($notif_id)
{
    include "../includes/connection.php";

    $stmt = $conn->prepare("UPDATE notification SET seen=1 WHERE id = ?");
    $stmt->bind_param("i", $notif_id);

    if ($stmt->execute()) {
        return "";
    } else {
        return "Something went wrong: " . $stmt->error;
    }
}

function getNotificationsList()
{
    include "../includes/connection.php";
    include "date-functions.php";
    include "user-functions.php";
    session_start();
    $notifications = getNotifications($_SESSION["user_id"]);

    $first = true;
    $cardMarkup = '';

    if (empty($notifications)) {
        if ($first) {
            $cardMarkup .= '<div class="card">';
            $cardMarkup .= '<h5 class="text-center mt-4" style="padding-bottom:15px">No Notifications</h5>';
            $first = false;
            $cardMarkup .= '</div>';
        }
    }

    if (!empty($notifications)) {
        foreach ($notifications as $notification) {
            $cardMarkup .= '<div class="card">';

            if ($first) {
                $cardMarkup .= '<h5 class="text-center mt-4">Notifications</h5><hr>';
                $first = false;
            }
            $cardMarkup .= '<div class="card-body d-flex align-items-center">';
            $cardMarkup .= '<div class="mr-4 d-flex align-items-center">';
            $cardMarkup .= '<i class="fa-solid fa-bell fa-2x" style="margin-right:10px;"></i>';
            $cardMarkup .= '<div class="ml-2">';
            $cardMarkup .= '<h6 class="card-title justify-content">';
            $cardMarkup .= $notification["text"] . '<a href="user-view-profile.php?id=' . $notification["about_id"] .'"> @'. getUserNameFromId($notification["about_id"]) . "</a><small> (" . getDateInterval($notification["timestamp"]) . ")</small>";
            $cardMarkup .= markAsSeen($notification["id"]);
            $cardMarkup .= '</h6>';
            $cardMarkup .= '</div>';
            $cardMarkup .= '</div>';
            $cardMarkup .= '</div>';
            $cardMarkup .= '</div>';
        }
    }
    return $cardMarkup;
}

$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "giveNotifications") {
        $response = getNotificationsList();
    }
    if ($_GET["action"] == "getNotificationCount") {
        $response = getNotificationCount($_GET["id"]);
    }
}
echo $response;

?>