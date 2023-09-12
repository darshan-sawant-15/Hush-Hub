<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-custom fixed-top" id="navbar">
    <a class="navbar-brand" href="index.php"><img src="./assets/images/applogo.svg" width="30" height="30"
            class="d-inline-block align-top" alt="app-logo">HushHub
        <?php if ($currentPage == "user-messages.php") { ?> <i id="toggleBtn" class="fa-solid fa-angle-left"
                onclick="toggleMsgList()" style="display:none"></i>
        <?php } ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <?php if ($currentPage != "login-form.php" && $currentPage!= "index.php") { ?>
            <form class="form-inline my-3 my-lg-0">
                <a href="login-form.php" class="btn btn-color"> <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </a>
            </form>
        <?php }
        if ($currentPage != "signup-form.php" && $currentPage!= "index.php") { ?>
            <form class="form-inline my-3 my-lg-0">
                <a href="signup-form.php" class="btn btn-color"> <i class="fa-solid fa-user-plus"></i>
                    SignUp
                </a>
            </form>
        <?php }
        ?>
    </div>
</nav>