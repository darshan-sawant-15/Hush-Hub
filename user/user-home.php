<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>
<!DOCTYPE html>
<html>


<head>
    <title>User Home Feed</title>
    <?php include "header.php"; ?>
    <link rel="stylesheet" href="../assets/css/common-post.css">
    <link rel="stylesheet" href="../assets/css/loader.css">
</head>
<?php
 ?>

<body>
    <?php include "base-user.php"; ?>
    <div>
        <div class="row pt-4 px-4 pb-1">
            <div class="col-md-6 offset-md-3">
                <div id="postContainer">

                </div>

            </div>
        </div>
    </div>
    <script src="../assets/js/user-home.js"></script>
</body>

</html>