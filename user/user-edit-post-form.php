<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Post</title>
    <?php include "header.php"; ?>

</head>



<body>
    <?php include "base-user.php"; ?>
    <div>
        <div class="row p-4">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center"> <i class="fa-solid fa-pen-to-square "></i>
                            <br>Edit Post
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

                        <?php
                        include "../function-files/post-functions.php";
                        $post = getPostFromId($_GET["id"]);
                        if ($post["user_id"] == $_SESSION["user_id"]) {
                            ?>


                            <form method="POST" action="../handlers/user/edit-post-handler.php">
                                <input type="hidden" name="postId" value="<?= $post["id"] ?>">
                                <div class="form-group">
                                    <label for="caption">
                                        Caption
                                    </label>
                                    <textarea name="caption" id="caption" class="form-control" rows="5"
                                        required><?= $post["caption"] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">
                                        Image
                                    </label>
                                    <div class="text-center">
                                        <img src="../assets/images/uploads/posts/<?= $post["image_name"] ?>"
                                            id="edit-post-img">
                                    </div>
                                </div>
                                <div class="form-group d-flex">
                                    <label for="like-chk">Show Like Count (to other accounts):</label>
                                    <input type="hidden" name="like-chk" value="0">
                                    <input type="checkbox" name="like-chk" id="like-chk" class="ml-auto" value="1" <?php if ($post["show_like_count"] == 1) { ?> checked <?php } ?>>
                                </div>
                                <div class="form-group d-flex">
                                    <label for="comm-chk">Allow Comments:</label>
                                    <input type="hidden" name="comm-chk" value="0">
                                    <input type="checkbox" name="comm-chk" id="comm-chk" class="ml-auto" value="1" <?php if ($post["allow_comments"] == 1) { ?> checked <?php } ?>>
                                </div>


                                <div class="text-center">
                                    <button type="submit" class="btn btn-custom">Save
                                        Changes</button>
                                </div>
                            </form>
                        <?php } else { ?>
                           <h5 class="text-center mt-4 pb-2" > You don't have permissions to edit this post </h5>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>