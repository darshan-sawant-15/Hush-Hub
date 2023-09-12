<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>

<!DOCTYPE html>
<html>


<head>
    <title>Create a Post</title>
    <?php include "header.php"; ?>
</head>



<body>
    <?php include "base-user.php"; ?>
    <div>
        <div class="row p-4">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center"> <i class="fa-solid fa-plus"></i> <br> Create a Post
                        </h4>
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


                        <form method="POST" action="../handlers/user/add-post-handler.php"
                            enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="caption">
                                    Caption
                                </label>
                                <textarea name="caption" id="caption" class="form-control" rows="6" required maxlength="280"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">
                                    Image
                                </label>
                                <input type="file" name="image" id="image" class="form-control-file" accept="image/*"
                                    required>
                                <div class="text-center mt-3" style="display:none" id="image-preview"></div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="like-chk">Show Like Count (to other accounts):</label>
                                <input type="hidden" name="like-chk" value="0">
                                <input type="checkbox" name="like-chk" id="like-chk" class="ml-auto" value="1" checked>
                            </div>
                            <div class="form-group d-flex">
                                <label for="comm-chk">Allow Comments:</label>
                                <input type="hidden" name="comm-chk" value="0">
                                <input type="checkbox" name="comm-chk" id="comm-chk" class="ml-auto" value="1" checked>
                            </div>


                            <div class="text-center">
                                <button type="submit" class="btn btn-custom">Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/image-preview.js"></script>

</body>

</html>