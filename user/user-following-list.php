<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>
<DOCTYPE html>
<html>


<head>
    <meta charset="ISO-8859-1">
    <title>Accounts You Follow</title>
    <?php include "header.php"; ?>
</head>


<body>
    <?php include "base-user.php"; ?>
    <div>
        <div class="row p-4">
            <div class="col-md-6 offset-md-3">
                <?php
                include "../function-files/user-functions.php";
                if (empty($_GET['id'])) {
                    $following_ids = getFollowingIDs($_SESSION["user_id"]);
                } else {
                    $following_ids = getFollowingIDs($_GET["id"]);
                }


                $first = true;
                if (!empty($following_ids)) {
                    foreach ($following_ids as $id) {
                        $user = getUserFromId($id);
                        ?>
                        <div class="card">
                            <?php if ($first) {
                                echo '<h5 class="text-center mt-4">Accounts You Follow</h5><hr>';
                                $first = false;
                            } ?>
                            <div class="card-body d-flex align-items-center">
                                <div class="mr-4">
                                    <img src="../assets/images/uploads/profile-pictures/<?php echo $user["profile_picture"] ?>" class="rounded-circle"
                                        style="border: 1px solid #17252A;" width="75" height="75" alt="Profile Picture">
                                </div>
                                <div>
                                    <h6 class="card-title">
                                        <?php echo "@" . $user["uname"] ?> •
                                        <?php echo $user["age"]; ?>
                                    </h6>
                                    <p class="card-text">
                                        <?php echo $user["fname"] ?>
                                    </p>
                                    <a href="<?php if ($user["id"] == $_SESSION["user_id"]) {
                                        echo "user-profile.php";
                                    } else {
                                        echo "user-view-profile.php?id=" . $user["id"];
                                    } ?>" class="btn btn-sm btn-custom">View
                                        Profile</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

            </div>
        </div>
    </div>




</body>

</html>