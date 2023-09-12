<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}?>
<!DOCTYPE html>
<html>

<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include "header.php"; ?>
    <link rel="stylesheet" href="../assets/css/user-profile.css">
</head>


<body>
    <?php include "base-user.php"; ?>

    <div class="ml-3">


        <div class="row">
            <div class="col-md-3 profile">
                <div class="text-center">
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
                    <img src="../assets/images/uploads/profile-pictures/<?php echo $_SESSION["profile_picture"] ?>"
                        alt="Profile Picture" class="profile-picture">
                    <h2 class="mt-3">
                        <?php echo $_SESSION["fname"] ?>
                    </h2>
                    <p class="text-muted">
                        <?php echo "@" . $_SESSION["username"] . " • " . $_SESSION["age"]; ?>
                    </p>
                    <p>
                        <?php echo $_SESSION["bio"]; ?>
                    </p>
                    <div class="d-flex justify-content-around">
                        <div class="followers-count">
                            <strong>Followers</strong>
                            <a href="user-followers-list.php">
                                <div class="text-center" id="followersCount">
                                    <?php
                                    echo getFollowersCount($_SESSION["user_id"]); ?>
                                </div>
                            </a>
                        </div>
                        <div class="following-count">
                            <strong>Following</strong>
                            <a href="user-following-list.php">
                                <div class="text-center" id="followingCount">
                                    <?php
                                    echo getFollowingCount($_SESSION["user_id"]); ?>
                                </div>
                            </a>
                        </div>
                    </div>
                    <a href="user-edit-profile.php" class="btn btn-block mt-3 btn-custom">Edit Profile</a>
                </div>
            </div>
            <div class="col-md-9">

                <?php
                include "../function-files/post-functions.php";
                include "../function-files/like-functions.php";
                include "../function-files/comment-functions.php";
                // Fetch and display user's posts from the database
                // Modify the code based on your database structure and retrieval method
                $userPosts = getPosts($_SESSION['user_id']);
                if ($userPosts->num_rows > 0) {
                    echo '<div class="posts-container">';
                    echo '<div class="row">';
                    while ($post = $userPosts->fetch_assoc()) {
                        echo '<div class="col-md-4">';


                        echo '<div class="post" title="Click on the post to interact" id=' . $post["id"] . '>';
                        echo '<div onclick="viewPost(' . $post["id"] . ')">';
                        echo '<img src="../assets/images/uploads/posts/' . $post['image_name'] . '" class="img-fluid post-img">';
                        echo '<hr>'; // Line separator
                        if (strlen($post["caption"]) > 30) {
                            echo '<p>' . substr($post['caption'], 0, 31) . '...</p>';
                        } else {
                            echo '<p>' . $post['caption'] . '</p>';
                        }
                        echo '</div>';
                        echo '<div class="post-actions mt-3">';
                        echo '<div class="btn-group d-flex align-items-center">';
                        echo '<div style="display:none" id="post-id">' . $post["id"] . '</div>';
                        if (!hasLiked($post["id"], $_SESSION["user_id"])) {
                            echo '<button class="btn btn-sm col-md-4 mr-2 no-click" id="like-btn" style="background-color: #ffbfb8; color:#17252A; border: 1px solid #B36F75; border-radius: 2px"><i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;" onclick="like(' . $post["id"] . ');"></i>';
                            if ($post["show_like_count"] == 1 || $post["user_id"]==$_SESSION["user_id"]) {
                                echo '<span id="like-count"';
                                if (getLikeCount($post["id"]) > 0) {
                                    echo 'onclick="showLikers(' . $post["id"] . ')"';
                                }
                                echo '>' . getLikeCount($post["id"]) . '</span>';
                            }
                            echo '</button>';
                        } else {
                            echo '<button class="btn btn-sm col-md-4 mr-2 no-click" id="like-btn" style="background-color: #ffbfb8; color:#17252A; border: 1px solid #B36F75; border-radius: 2px"><i class="fa-sharp fa-solid fa-heart" style="color: red; margin-right:10px;" onclick="unlike(' . $post["id"] . ');"></i>';
                            if ($post["show_like_count"] == 1 || $post["user_id"]==$_SESSION["user_id"]) {
                                echo '<span id="like-count"';
                                if (getLikeCount($post["id"]) > 0) {
                                    echo 'onclick="showLikers(' . $post["id"] . ')"';
                                }
                                echo '>' . getLikeCount($post["id"]) . '</span>';
                            }
                        }
                        echo '<button class="btn btn-sm col-md-4 mr-2 no-click" style="background-color: white; border: 1px solid #2B7A78; border-radius: 2px" onclick=\'window.location.href="user-view-post.php?id=' . $post["id"] . '"\'';
                        if ($post["allow_comments"] == 0) {
                            echo 'disabled';
                        }
                        echo '><i class="fa-regular fa-comment no-click" style="color: #2b7a78; margin-right:10px"></i>';
                        if ($post["allow_comments"] == 1) {
                            echo getCommentCount($post["id"]);
                        }
                        echo '</button>';
                        echo '<button class="btn btn-sm col-md-4 no-click" style="background-color: white; border: 1px solid #2B7A78; border-radius: 2px"  onclick="shareContent();"><i class="fa-regular fa-share-from-square" style="color: #2b7a78;"></i></button>';
                        echo '</div>';
                        echo '</div>';

                        // 
                
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="text-center d-flex justify-content-center align-items-center" style="height: 60vh;">';
                    echo '<div class="d-flex justify-content-center flex-column align-items-center" style="height: 60vh;">';
                    echo '<a href="user-post-form.php"><img src="../assets/images/nopost.png" alt="No Posts" class="img-fluid" style="height: 200px; width: 200px;">';
                    echo '<h5 class="mt-3">Create Your First Post.</h5></a>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>

            </div>
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/js/user-profile.js"></script>
</body>

</html>