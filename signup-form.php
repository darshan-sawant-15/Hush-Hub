<?php session_start(); ?>
<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <?php include "header.php"; ?>
</head>

<body>
    <?php include "base.php"; ?>
    <div>
        <div class="row p-4">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fa-solid fa-user-plus fa-2x"></i>
                            <h4>Registration</h4>
                        </div>
                        <?php if (isset($_SESSION['successMessage'])) { ?>
                            <div class="alert alert-success text-center">
                                <?php echo $_SESSION['successMessage'];
                                unset($_SESSION["successMessage"]); ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['errorMessage'])) { ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $_SESSION['errorMessage'];
                                unset($_SESSION["errorMessage"]); ?>
                            </div>
                        <?php } ?>
                        <form action="handlers/signup-handler.php" method="post" onsubmit="return validate()">
                            <div class="form-group">
                                <label for="fname">Full Name</label>
                                <input type="text" required="required" class="form-control" name="fname"
                                    maxlength="255">
                            </div>
                            <div class="form-group">
                                <label>Age</label> <input type="number"
                                    onKeyPress="if(this.value.length==2) return false;" required="required"
                                    class="form-control" name="age" min=13>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label> <input type="email" class="form-control"
                                    id="exampleInputEmail1" aria-describedby="emailHelp" name="email" required
                                    maxlength="255">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Phone No.</label><br> <input type="number"
                                    class="form-control" id="phone" aria-describedby="emailHelp" name="phone"
                                    pattern="^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$"
                                    title="Valid 10 digit Indian phone number" required
                                    onKeyPress="if(this.value.length==10) return false;">

                                <div class="p-conf">Number Verified</div>

                                <div id="recaptcha-container" style="margin-top:20px; margin-bottom:20px;"></div>
                                <input type="button" class="btn badge-pill btn-custom" value="Send OTP"
                                    onclick="phoneAuth()" id="sendotp">
                            </div>


                            <div class="form-group" id="verifier" style="display: none;">
                                <label for="exampleInputEmail1">Enter OTP</label> <input type="number"
                                    class="form-control" id="otpverify" aria-describedby="emailHelp" name="otpverify"
                                    pattern="^[0-9]{1,6}$" title="6 digit OTP"
                                    onKeyPress="if(this.value.length==6) return false;"> <br>
                                <input type="button" class="btn badge-pill btn-custom" value="Verify"
                                    onclick="codeverify()">
                                <div class="n-conf">Incorrect OTP</div>
                            </div>

                            <div class="form-group">
                                <label>Username </label> <input type="text" required="required" class="form-control"
                                    name="uname" minlength="4" maxlength="30" id="uname"
                                    pattern="^(?=.*[a-zA-Z])[a-zA-Z0-9_-]{4,30}$"
                                    title="4-30 characters, at least one English letter, numbers allowed, only underscore(_) and dash(-) allowed, spaces not allowed">
                                <div class="invalid-feedback">
                                    This username is already taken
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label> <input type="password"
                                    class="form-control" id="password" name="password" required
                                    pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$"
                                    title="Minimum eight characters, at least one upper case English letter, one lower case English letter, one number and one special character"
                                    minlength="8" maxlength="128">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Confirm Password</label> <input required
                                    type="password" class="form-control" id="cpassword" name="cpassword">
                            </div>
                            <br>
                            <button type="submit" id="submitbtn" class="btn badge-pill btn-block btn-custom" disabled
                                title="Verify phone number to sign up">Sign
                                up</button>

                            <br>
                            <div class="form-group text-center">

                                <p>
                                    Have an account? <a href="login-form.php">Log-in</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/register-validation.js"></script>
    <script type="module" src="assets/js/otp-verification.js"></script>
    <script type="module">
        import { phoneAuth, codeverify, render } from './assets/js/otp-verification.js';
        window.phoneAuth = phoneAuth;
        window.codeverify = codeverify;
        window.addEventListener("load", render);
    </script>
</body>

</html>