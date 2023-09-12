<?php

function checkIfEmailExists($email)
{
    include "connection.php";
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        return true;
    }
    $stmt->close();
    return false;
}

function checkIfPhoneExists($phone)
{
    include "connection.php";
    $stmt = $conn->prepare("SELECT * FROM user WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        return true;
    }
    $stmt->close();
    return false;
}


function checkIfUsernameExists($uname)
{
    include "connection.php";
    $stmt = $conn->prepare("SELECT * FROM user WHERE uname = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        return true;
    }
    $stmt->close();
    return false;
}

function isPrivate($userId)
{
    $privacy = "Private";
    include "connection.php";
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM user WHERE id = ? and privacy=?");
    $stmt->bind_param("is", $userId, $privacy);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row["count"] > 0) {
            return true;
        } else {
            return false;
        }
    }
}

function getSearchedUsers($searchTerm)
{
    include "../includes/connection.php";
    $searchTerm = $searchTerm . "%";
    $stmt = $conn->prepare("SELECT * FROM user WHERE uname LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getUserFromId($id)
{
    include "../includes/connection.php";
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = "";
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
    return $user;
}

function giveSearchedUsers($searchTerm)
{
    session_start();

    if (!empty($searchTerm)) {
        $searchTerm = strtolower($searchTerm);

        $users = getSearchedUsers($searchTerm);
        $html = "";
        $userCount = 0;
        if ($users->num_rows > 0) {
            while ($user = $users->fetch_assoc()) {
                if ($user["id"] != $_SESSION["user_id"]) {
                    $html .= '<div class="card mt-2">';
                    $html .= '<div class="card-body d-flex align-items-center">';
                    $html .= '<div class="mr-4">';
                    $html .= '<img src="../assets/images/uploads/profile-pictures/' . $user["profile_picture"] . '" class="rounded-circle"  width="75" height="75" style="border: 1px solid #17252A;"
                                    width="100" height="100" alt="Profile Picture">';
                    $html .= '</div>';
                    $html .= '<div>';
                    $html .= '<h6 class="card-title">';
                    $html .= '@' . $user["uname"] . ' • ' . $user["age"];
                    $html .= '</h6>';
                    $html .= '<p class="card-text">' . $user["fname"] . '</p>';
                    $html .= '<a href="user-view-profile.php?id=' . $user['id'] . '" class="btn btn-sm btn-custom"
                                    >View Profile</a>';
                    $html .= '</div></div></div>';
                    $userCount++;
                }
            }

        }
        if ($userCount == 0) {
            $html .= '<div class="card mt-2">';
            $html .= '<h5 class="text-center mt-4" style="padding-bottom:15px">No Such Active User</h5>';
            $html .= '</div>';
        }
        return $html;
    }
}

function getUserNameFromId($id)
{
    include "../includes/connection.php";
    $stmt = $conn->prepare("SELECT uname FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userName = "";
    if ($result->num_rows == 1) {
        $userName = $result->fetch_assoc()["uname"];
    }
    $stmt->close();
    return $userName;
}


function getUserImageFromId($id)
{
    include "../includes/connection.php";
    $stmt = $conn->prepare("SELECT profile_picture FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userImage = "";
    if ($result->num_rows == 1) {
        $userImage = $result->fetch_assoc()["profile_picture"];
    }
    $stmt->close();
    return $userImage;
}



function giveReqUsers()
{
    include "../includes/connection.php";
    include "follow-functions.php";
    session_start();
    $requestor_ids = getRequestorIDs($_SESSION["user_id"]);

    $first = true;
    $cardMarkup = '';

    if (empty($requestor_ids)) {
        if ($first) {
            $cardMarkup .= '<div class="card">';
            $cardMarkup .= '<h5 class="text-center mt-4" style="padding-bottom:15px">No Pending Follow Requests</h5>';
            $first = false;
            $cardMarkup .= '</div>';
        }
    }

    if (!empty($requestor_ids)) {
        foreach ($requestor_ids as $id) {
            $user = getUserFromId($id);

            $cardMarkup .= '<div class="card">';
            if ($first) {
                $cardMarkup .= '<h5 class="text-center mt-4">Pending Follow Requests</h5><hr>';
                $first = false;
            }
            $cardMarkup .= '<div class="card-body d-flex align-items-center">';
            $cardMarkup .= '<div class="mr-4">';
            $cardMarkup .= '<img src="../assets/images/uploads/profile-pictures/' . $user["profile_picture"] . '" class="rounded-circle" style="border: 1px solid #17252A;" width="75" height="75" alt="Profile Picture">';
            $cardMarkup .= '</div>';
            $cardMarkup .= '<div>';
            $cardMarkup .= '<h6>';
            $cardMarkup .= '@' . $user["uname"] . ' • ' . $user["age"];
            $cardMarkup .= '</h6>';
            $cardMarkup .= '<p class="card-text">' . $user["fname"] . '</p>';
            $cardMarkup .= '<a href="';
            if ($user["id"] == $_SESSION["user_id"]) {
                $cardMarkup .= 'user-profile.php';
            } else {
                $cardMarkup .= 'user-view-profile.php?id=' . $user["id"];
            }
            $cardMarkup .= '" class="btn btn-sm" style="background-color: #17252A; color:white">View Profile</a>';
            $cardMarkup .= '</div>';
            $cardMarkup .= '<div class="ml-auto mt-2">';
            $cardMarkup .= '<button class="btn btn-sm btn-success mb-2" style="width:80px;" onclick="acceptRequest(' . $user["id"] . ',' . $_SESSION["user_id"] . ');">Accept <i class="fa-solid fa-check"></i></button>';
            $cardMarkup .= '<br>';
            $cardMarkup .= '<button class="btn btn-sm btn-danger" style="width:80px;" onclick="rejectRequest(' . $user["id"] . ',' . $_SESSION["user_id"] . ');">Reject <i class="fa-solid fa-xmark"></i></button>';
            $cardMarkup .= '</div>';
            $cardMarkup .= '</div>';

        }
    }
    return $cardMarkup;

}

function giveMessengers()
{
    include "../includes/connection.php";
    include "follow-functions.php";
    include "message-functions.php";
    session_start();
    $messengersIds = getBothWaysFollow($_SESSION["user_id"]);


    $cardMarkup = "";
    if (!empty($messengersIds)) {
        foreach ($messengersIds as $id) {
            $user = getUserFromId($id);
            $lastMessage = getLastMessage($_SESSION["user_id"], $id);
            $lastMessageSender = "";
            if (!empty($lastMessage)) {
                if ($lastMessage["sender_id"] != $_SESSION["user_id"]) {
                    $lastMessageSender = getUserNameFromId($lastMessage["sender_id"]) . ': ';
                } else {
                    $lastMessageSender = "You: ";
                }
            }

            $cardMarkup .= '<div class="card mt-3 messenger" id=' . $user["id"] . '  onclick="messengerSelected(' . $user["id"] . ')">';

            $cardMarkup .= '<div class="card-body d-flex align-items-center">';
            $cardMarkup .= '<div class="mr-4">';
            $cardMarkup .= '<img src="../assets/images/uploads/profile-pictures/' . $user["profile_picture"] . '" class="rounded-circle" style="border: 1px solid #17252A;" width="50" height="50" alt="Profile Picture">';
            $cardMarkup .= '</div>';
            $cardMarkup .= '<div>';
            $cardMarkup .= '<h6 class="card-title">';
            $cardMarkup .= '<strong>@' . $user["uname"] . '</strong>';
            $cardMarkup .= '<input type="hidden" value="' . $user["uname"] . '" id="uname">';
            $cardMarkup .= '</h6>';

            if (!empty($lastMessage)) {
                $cardMarkup .= '<p class="card-text" id="latestMsg">';
                $cardMarkup .= '<span id=' . $lastMessage["id"] . '></span>';
                if ($lastMessage["seen"] == 0 && $lastMessage["receiver_id"] == $_SESSION["user_id"]) {
                    $cardMarkup .= '<strong>' . $lastMessageSender . $lastMessage["message"] . '</strong>';
                } else {
                    $cardMarkup .= $lastMessageSender . $lastMessage["message"];
                }
                $cardMarkup .= '</p>';
            }
            $cardMarkup .= '</div>';
            $cardMarkup .= '</div>';
            $cardMarkup .= '</div>';
        }


    } else {
        $cardMarkup = '<div class="card mt-3 text-center">';
        $cardMarkup .= '<div class="text-center mt-4" style="padding-bottom:15px">';
        $cardMarkup .= '<h6 >No People to Message </h6> <p>Follow People and have them Follow You Back to Start A Conversation</p>';
        $cardMarkup .= '</div>';
        $cardMarkup .= '</div>';
    }
    return $cardMarkup;
}



$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "giveSearchedUsers") {
        $response = giveSearchedUsers($_GET["searchTerm"]);
    }
    if ($_GET["action"] == "giveReqUsers") {
        $response = giveReqUsers();
    }
    if ($_GET["action"] == "giveMessengers") {
        $response = giveMessengers();
    }
    if ($_GET["action"] == "checkUsername") {
        $response = checkIfUsernameExists($_GET["uname"]);
    }
}
echo $response;
?>