<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="ISO-8859-1">
    <title>Notifications</title>
    <?php include "header.php" ?>
     <link rel="stylesheet" href="../assets/css/loader.css">
    <style>

    </style>
</head>


<body>


    <?php include "base-user.php";
    include "../function-files/notification-functions.php";
    include "../function-files/follow-functions.php" ?>
    <input type="hidden" value="<?= $_SESSION["user_id"] ?>" name="user_id" id="user_id">
    <input type="hidden" value="<?= $_SESSION["privacy"] ?>" name="user_privacy" id="user_privacy">
    <div>
        <?php if ($_SESSION["privacy"] == "Private") { ?>
            <div class=" container-fluid">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <button class="btn btn-block btn-notif" onclick="getNotifications()" id="notBtn">
                            <h5 id="notText">Notifications
                                <?php echo "(" . getNotificationCount($_SESSION["user_id"]) . ")"; ?>
                            </h5>
                        </button>
                    </div>

                    <div class="col-md-6 mt-3">
                        <button class="btn btn-block btn-notif" onclick="getFollowRequests()" id="followBtn">
                            <h5 id="reqText">Follow Requests
                                <?php echo "(" . getRequestCount($_SESSION["user_id"]) . ")"; ?>
                            </h5>
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class=" row p-4">
            <div class="col-md-6 offset-md-3">
                <div id="usersContainer">
                    <div class="loader-container">
                        <div class="loader"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="../assets/js/user-notifications.js"></script>
    <script>
        window.onload = function () {
            // Get the button element
            <?php if ($_SESSION["privacy"] == "Public") { ?>
                getNotifications();
            <?php } else {
                if (getNotificationCount($_SESSION["user_id"]) >= getRequestCount($_SESSION["user_id"])) { ?>
                    var button = document.getElementById('notBtn');
                <?php } else { ?>
                    var button = document.getElementById('followBtn');
                <?php } ?>
                button.click();
            <?php } ?>


            // Simulate a click on the button

        };
    </script>


</body>

</html>