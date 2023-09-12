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
    <title>Edit Profile</title>
    <?php include "header.php"; ?>



</head>

<body>

    <?php include "base-user.php"; ?>

    <div>
        <div class="row p-4">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fa-solid fa-user fa-2x"></i>
                            <h4>Edit Profile</h4>
                        </div>

                        <?php if (isset($_SESSION['errorMessage'])) { ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $_SESSION['errorMessage']; ?>
                            </div>
                            <?php unset($_SESSION["errorMessage"]);
                        } ?>

                        <form action="../handlers/user/edit-profile-handler.php" method="post"
                            enctype="multipart/form-data" onsubmit="return validate()">
                            <!-- <input type="hidden" value="${userobj.id }" name="id"> -->


                            <div class="form-group">
                                <label>Full Name </label> <input type="text" required="required" class="form-control"
                                    name="fname" value="<?php echo $_SESSION["fname"] ?>" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>User Name </label> <input type="text" required="required" class="form-control"
                                    name="uname" id="uname" value="<?php echo $_SESSION["username"] ?>" minlength="4"
                                    maxlength="30" pattern="^(?=.*[a-zA-Z])[a-zA-Z0-9_-]{4,30}$"
                                    title="4-30 characters, at least one English letter, numbers allowed, only underscore(_) and dash(-) allowed, spaces not allowed">
                                <div class="invalid-feedback">
                                    This username is already taken
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Age</label> <input type="number" required="required" class="form-control"
                                    name="age" value="<?php echo $_SESSION["age"] ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label> <input type="email" class="form-control"
                                    id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                                    value="<?php echo $_SESSION["email"] ?>" required maxlength="255">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Phone No.</label> <input type="number"
                                    class="form-control" id="phone" aria-describedby="emailHelp" name="phone"
                                    value="<?php echo $_SESSION["phone"] ?>"
                                    pattern="^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$"
                                    title="Valid 10 digit Indian phone number" required onchange="phoneChange();"
                                    onkeypress="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();">
                                <div class="p-conf">Number Verified</div>
                                <div id="recaptcha-container" style="margin-top:20px; margin-bottom:20px; display:none"></div>
                                <input type="button" class="btn btn-custom badge-pill" value="Send OTP"
                                    onclick="phoneAuth()" id="sendotp">
                            </div>


                            <div class="form-group" id="verifier" style="display: none;">
                                <label for="exampleInputEmail1">Enter OTP</label> <input type="number"
                                    class="form-control" id="otpverify" aria-describedby="emailHelp" name="otpverify"
                                    pattern="^[0-9]{1,6}$" title="6 digit OTP"
                                    onKeyPress="if(this.value.length==6) return false;"> <br> <input
                                    type="button btn-custom" class="btn badge-pill" value="Verify"
                                    onclick="codeverify()">
                                <div class="n-conf">Incorrect OTP</div>
                            </div>

                            <div class="form-group">
                                <label>Bio </label> <input type="text" class="form-control" name="bio" maxlength="100"
                                    value="<?php echo $_SESSION["bio"] ?>">
                            </div>

                            <div class="form-group">
                                <label for="image">
                                    Profile Picture
                                </label>
                                <input type="file" name="image" id="image" class="form-control-file" accept="image/*">

                                <div class="text-center mt-3" id="image-preview">
                                    <img src="../assets/images/uploads/profile-pictures/<?= $_SESSION["profile_picture"] ?>"
                                        id="edit-profile-picture">
                                </div>
                                <div class="text-center">
                                    <a class="btn btn-sm btn-danger mt-3 text-white" id="removeBtn" onclick="removeProfilePicture()">Remove</a>
                                    <input type="hidden" name="image-removed" id="image-removed" value="0">
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Privacy </label>
                                <select name="privacy" class="custom-select" id="inlineFormCustomSelectPref" required>
                                    <option selected value="<?php echo $_SESSION["privacy"] ?>" hidden>
                                        <?php echo $_SESSION["privacy"] ?>
                                    </option>
                                    <option value="Private">Private</option>
                                    <option value="Public">Public</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn " id="passwchg" onclick="passwordSec()"
                                    style="background-color: #2B7A78; color: white;">
                                    <i id="picon" class="fa-solid fa-pen-to-square"></i> Change
                                    Password
                                </button>
                            </div>



                            <div id="password" style="display: none;">
                                <input type="hidden" id="old-passhash" value="<?= $_SESSION["password"] ?>">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Current Password</label>
                                    <input type="password" class="form-control" id="cpassword" name="cpassword">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">New Password</label> <input type="password"
                                        class="form-control" id="npassword" name="npassword"
                                        pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$"
                                        title="Minimum eight characters, at least one upper case English letter, one lower case English letter, one number and one special character"
                                        minlength="8" maxlength="128">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Confirm Password</label>
                                    <input type="password" class="form-control" id="ccpassword" name="ccpassword"
                                        value="<?php echo $_SESSION["password"] ?>">
                                </div>

                            </div>

                            <button type="submit" class="btn badge-pill btn-block btn-custom" id="updatebtn">Update
                                Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/js/user-edit-profile.js"></script>
    <script src="../assets/js/user-edit-profile-username-val.js"></script>

    <script type="module" src="../assets/js/otp-verification.js"></script>
    <script type="module">
        import { phoneAuth, codeverify, render } from '../assets/js/otp-verification.js';
        window.phoneAuth = phoneAuth;
        window.codeverify = codeverify;
        window.addEventListener("load", render);
    </script>

    <script>
        window.addEventListener("load", phoneChange);
    </script>
</body>

</html>