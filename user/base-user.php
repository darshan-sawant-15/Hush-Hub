<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-custom fixed-top" id="navbar">
    <a class="navbar-brand" href="#">
        <?php if ($currentPage == "user-messages.php") { ?> <i id="toggleBtn" class="fa-solid fa-angle-left"
                onclick="toggleMsgList()" style="display:none"></i>
        <?php } ?><img src="../assets/images/applogo.svg" width="30" height="30" class="d-inline-block align-top"
            alt="">HushHub

    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php
            if ($currentPage != "user-home.php") { ?>
                <li class="nav-item active"><a class="nav-link" href="user-home.php"><i class="fa-solid fa-house"></i>
                        Home
                        <span class="sr-only">(current)</span></a></li>
            <?php }
            if ($currentPage != "user-post-form.php") { ?>
                <li class="nav-item active"><a class="nav-link" href="user-post-form.php"><i class="fa-solid fa-plus"></i>
                        Post
                        <span class="sr-only">(current)</span></a></li>
            <?php }

            if ($currentPage != "user-search.php") { ?>

                <li class="nav-item active"><a class="nav-link" href="user-search.php"><i class="fa-solid fa-search"></i>
                        Search Users
                        <span class="sr-only">(current)</span></a></li>
            <?php }

            if ($currentPage != "user-messages.php") { ?>
                <?php include('../function-files/message-functions.php') ?>
                <li class="nav-item active"><a class="nav-link" href="user-messages.php"><i class="fa-solid fa-message"></i>
                        Messages <span class="badge badge-dark" style="background-color: red;">
                            <?php echo getUnseenMessageCount($_SESSION["user_id"]) ?>
                        </span>
                        <span class="sr-only">(current)</span></a></li>
            <?php }
            ?>
        </ul>
        <?php
        if ($currentPage != "user-profile.php") { ?>
            <form class="form-inline my-3 my-lg-0">
                <a href="user-profile.php" class="btn btn-custom mr-3"> <i class="fa-solid fa-user"></i>
                    Your Profile
                </a>
            </form>
        <?php }
        if ($currentPage != "user-notifications.php") { ?>
            <form class="form-inline my-3 my-lg-0">
                <a href="user-notifications.php" class="btn btn-custom mr-3"> <i class="fa-solid fa-bell"></i>
                    Notifications <span class="badge badge-dark" style="background-color: red;">
                        <?php include "../function-files/notification-functions.php";
                        include "../function-files/follow-functions.php";
                        if (getRequestCount($_SESSION["user_id"]) <= getNotificationCount($_SESSION["user_id"])) {
                            echo getNotificationCount($_SESSION["user_id"]);
                        } else {
                            echo getRequestCount($_SESSION["user_id"]);
                        } ?>
                    </span>
                </a>
            </form>
        <?php } ?>
        <form class="form-inline my-3 my-lg-0">
            <a href="../handlers/user/logout-handler.php" class="btn btn-custom"> <i
                    class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </form>
    </div>
</nav>