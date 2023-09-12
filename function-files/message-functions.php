<?php
function addMessage($senderId, $receiverId, $messageText)
{
    include "../includes/connection.php";
    include "encryption-functions.php";
    session_start();
    $userId = $_SESSION["user_id"];

    //encryption
    $key = getRandomKey();
    $iv = getRandomIV();
    $encryptedMessage = openssl_encrypt($messageText, 'aes-256-cbc', $key, 0, $iv);
    $encryption_info = base64_encode($key) . "|" . base64_encode($iv);

    $sql = "INSERT INTO message(sender_id, receiver_id, message, encryption_info) VALUES(?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $senderId, $receiverId, $encryptedMessage, $encryption_info);

    if ($stmt->execute()) {
        $response = "Messaged";
    } else {
        $response = "Error adding message" . mysqli_error($conn);
    }
    return $response;
}

function getMessages($senderId, $receiverId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM message WHERE (sender_id=? OR sender_id=?) AND (receiver_id=? OR receiver_id=?) ORDER BY timestamp ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $senderId, $receiverId, $receiverId, $senderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($message = $result->fetch_assoc()) {
        //decryption 
        $encryptionInfo = $message["encryption_info"];
        list($keyBase64, $ivBase64) = explode("|", $encryptionInfo);

        $key = base64_decode($keyBase64);
        $iv = base64_decode($ivBase64);
        $decryptedMessage = openssl_decrypt($message["message"], 'aes-256-cbc', $key, 0, $iv);
        $message["message"] = $decryptedMessage;


        $messages[] = $message;
    }

    return $messages;
}

function getMessageCount($senderId, $receiverId)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM message WHERE (sender_id=? OR sender_id=?) AND (receiver_id=? OR receiver_id=?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $senderId, $receiverId, $receiverId, $senderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

function checkIfNewMessage($senderId, $receiverId, $lastMessageId)
{
    include "../includes/connection.php";

    $sql = "SELECT id FROM message WHERE (sender_id=? OR sender_id=?) AND (receiver_id=? OR receiver_id=?) ORDER BY timestamp DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $senderId, $receiverId, $receiverId, $senderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = $result->fetch_assoc();
        if ($message["id"] != $lastMessageId) {
            return "Yes";
        }
    }
    return "No";
}

function getLastMessage($senderId, $receiverId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM message WHERE (sender_id=? OR sender_id=?) AND (receiver_id=? OR receiver_id=?) ORDER BY timestamp DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $senderId, $receiverId, $receiverId, $senderId);
    $stmt->execute();

    $result = $stmt->get_result();

    $message = null;

    if ($result->num_rows > 0) {
        $message = $result->fetch_assoc();
        $encryptionInfo = $message["encryption_info"];
        list($keyBase64, $ivBase64) = explode("|", $encryptionInfo);

        $key = base64_decode($keyBase64);
        $iv = base64_decode($ivBase64);
        $decryptedMessage = openssl_decrypt($message["message"], 'aes-256-cbc', $key, 0, $iv);
        $message["message"] = $decryptedMessage;
    }

    $stmt->close();
    return $message;
}

function giveLastMessage($senderId, $receiverId, $lastMessageId)
{
    session_start();
    include "user-functions.php";
    $message = getLastMessage($senderId, $receiverId);
    markMessageAsSeen($message, $_SESSION["user_id"]);

    $html = '<div class="message lastMessage" id="' . $message["id"] . '" >';
    $html .= '<span class="sender">' . getUserNameFromId($message["sender_id"]) . ': </span>';
    $html .= '<span class="text">' . $message["message"] . '</span>';
    $html .= '</div>';

    return $html;
}

function getUnseenMessageCount($receiverId)
{
    include "../includes/connection.php";

    $sql = "SELECT COUNT(*) as count FROM message WHERE receiver_id=? AND seen=0";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $receiverId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    $stmt->close();
}

function markMessageAsSeen($message, $userId)
{
    include "../includes/connection.php";
    if ($message["receiver_id"] == $userId) {
        $messageId = $message["id"];
        $sql = "UPDATE message SET seen=1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $messageId);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    return false;
}

function getOverallLastMessageForUser($userId)
{
    include "../includes/connection.php";

    $sql = "SELECT * FROM message WHERE sender_id=? OR receiver_id=? ORDER BY timestamp DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $message=null;
    if ($result->num_rows > 0) {
        $message = $result->fetch_assoc();
        $encryptionInfo = $message["encryption_info"];
        list($keyBase64, $ivBase64) = explode("|", $encryptionInfo);
        $key = base64_decode($keyBase64);
        $iv = base64_decode($ivBase64);
        $decryptedMessage = openssl_decrypt($message["message"], 'aes-256-cbc', $key, 0, $iv);
        $message["message"] = $decryptedMessage;
    }

    $stmt->close();
    return $message;
}


function giveMessageUI($senderId, $receiverId)
{
    include "user-functions.php";
    session_start();
    $messages = getMessages($senderId, $receiverId);

    if (!empty($messages)) {
        $html = "";
        $messageCount = getMessageCount($senderId, $receiverId);
        $html .= '<input type="hidden" id="receiver" value=' . $receiverId . ' />';
        $messagesCount = count($messages);

        foreach ($messages as $currentIndex => $message) {
            markMessageAsSeen($message, $_SESSION["user_id"]);
            $messageClass = ($currentIndex == ($messagesCount - 1)) ? 'lastMessage' : '';

            $html .= '<div class="message ' . $messageClass . '" id="' . $message["id"] . '">';
            $html .= '<span class="sender">' . getUserNameFromId($message["sender_id"]) . ': </span>';
            $html .= '<span class="text">' . $message["message"] . '</span>';
            $html .= '</div>';
        }
    } else {
        $html = '<input type="hidden" id="receiver" value=' . $receiverId . ' />';
        $html .= '<input type="hidden" id="messageCount" value="0" />';
        $html .= '<div class="loader-container"> Type your first message and start the conversation</div>';
        $html .= '<div class="message lastMessage" id="0"></div>';
    }

    return $html;
}


$response = "";
if (!empty($_GET["action"])) {
    if ($_GET["action"] == "giveMessageUI") {
        $response = giveMessageUI($_GET["sender_id"], $_GET["receiver_id"]);
    }

    if ($_GET["action"] == "message") {
        $response = addMessage($_GET["sender_id"], $_GET["receiver_id"], $_GET["messageText"]);
    }

    if ($_GET["action"] == "getMessageCount") {
        $response = getMessageCount($_GET["sender_id"], $_GET["receiver_id"]);
    }
    if ($_GET["action"] == "checkIfNewMessage") {
        $response = checkIfNewMessage($_GET["sender_id"], $_GET["receiver_id"], $_GET["lastMessageId"]);
    }
    if ($_GET["action"] == "appendNewMessage") {
        $response = giveLastMessage($_GET["sender_id"], $_GET["receiver_id"], $_GET["lastMessageId"]);
    }
    if ($_GET["action"] == "getOverallLastMessage") {
        $response = getOverallLastMessageForUser($_GET["user_id"]);
        $response = json_encode($response);
    }
}
echo $response;

?>