<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>

<!DOCTYPE html>
<html>



<head>
    <title>Messaging UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php
    include "header.php";
    if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
        // Redirect to the login page or any other appropriate action
        header('Location: ../login-form.php');
        exit();
    } ?>
    <link rel="stylesheet" href="../assets/css/user-messages.css">
    <link rel="stylesheet" href="../assets/css/loader.css">
</head>


<body>
    <?php include "base-user.php" ;
    include "../function-files/message-functions.php";
    $overallLastMsg = getOverallLastMessageForUser($_SESSION["user_id"]);
    ?>
    <input type="hidden" value="<?= $_SESSION["user_id"] ?>" name="sender_id" id="sender_id">
    <input type="hidden" value="<?= $overallLastMsg["id"] ?>" name="olm_id"  id="olm_id">
    <div>
        <div class="row margin">
            <div class="messengers">
                <h4>Chats <i class="fa-regular fa-comment"></i></h4>
                <div id="messengers-container">
                    <div class="loader-container">
                        <div class="loader"></div>
                    </div>
                </div>

                <!-- Add more messengers here -->

            </div>
            <div class="chat-room mr-2 mb-auto" id="chat-room" style="display:none" onclick="hideMsgList()">
                <div class="chat-messages" style="overflow-y: scroll;" id="message-container">
                    <!-- Chat messages will be dynamically loaded here -->
                    <input type="hidden" id="receiver" value="0" />
                    <input type="hidden" id="messageCount" value="0" />

                </div>
                <div class="message-input mb-2">
                    <input type="text"class="form-control" id="messageToSend" placeholder="Type your message..." maxlength="5000">
                    <button class="btn btn-md btn-custom" onclick="sendMessage();">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/user-messages.js"></script>
</body>

</html>