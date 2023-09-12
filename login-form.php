<?php session_start(); ?>
<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <?php include "header.php";
    ?>
</head>

<body>
    <?php include "base.php"; ?>
    <div>
        <div class="row p-4">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fa-solid fa-right-to-bracket fa-2x"></i>
                            <h4>Login</h4>
                        </div>

                        <?php if (isset($_SESSION['errorMessage'])): ?>
                            <div class="alert alert-danger text-center">
                                <?= $_SESSION['errorMessage'];
                                unset($_SESSION["errorMessage"]); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info text-center">
                                <?= $_SESSION['message'];
                                unset($_SESSION["message"]); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="handlers/login-handler.php">
                            <div class="form-group">
                                <label for="uname">Username</label>
                                <input type="text" required class="form-control" name="uname">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <button type="submit" class="btn btn-custom btn-block">Login</button>

                            <div class="form-group text-center mt-3">
                                <p>New to HushHub? <a href="signup-form.php">Sign-up</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>