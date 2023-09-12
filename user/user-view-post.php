<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>
<!DOCTYPE html>
<html>


<head>
    <title>Post View</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- AddToAny share buttons -->
    <script async src="https://static.addtoany.com/menu/page.js"></script>

    <?php include "header.php"; ?>
    <link rel="stylesheet" href="../assets/css/common-post.css">
    <link rel="stylesheet" href="../assets/css/loader.css">
</head>


<body>
    <?php include "base-user.php"; ?>
    <input type="hidden" value="<?= $_GET["id"] ?>" id="post_id">
    <div>

        <div class=" row pt-4 px-4 pb-1">

            <div class="col-md-6 offset-md-3">
                <?php if (isset($_SESSION['successMessage'])) { ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['successMessage']; ?>
                    </div>
                    <?php unset($_SESSION["successMessage"]);
                } ?>


                <?php if (isset($_SESSION['errorMessage'])) { ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $_SESSION['errorMessage']; ?>
                    </div>
                    <?php unset($_SESSION["errorMessage"]);
                } ?>
                <div id="postContainer">
                    <div class="loader-container">
                        <div class="loader"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="../assets/js/user-view-post.js"></script>



</body>

</html>