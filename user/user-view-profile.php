<?php session_start(); 
if (!isset($_SESSION['username']) || $_SESSION['role'] != "user") {
    // Redirect to the login page or any other appropriate action
    header('Location: ../login-form.php');
    exit();
}

if ($_SESSION["user_id"] == $_GET["id"]) {
    header('Location: user-view-profile.php');
}?>
<!DOCTYPE html>
<html>


<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include "header.php"; ?>
    <link rel="stylesheet" href="../assets/css/user-profile.css">
</head>

<body>
    <?php include "base-user.php"; ?>
    <input type="hidden" value="<?= $_SESSION["user_id"] ?>" id="user_id">
    <input type="hidden" value="<?= $_GET["id"] ?>" id="profile_id">

    <div class="ml-3">


        <div class="row">
            <div class="col-md-3 profile">
                <div class="text-center">
                    <?php
                    include "../function-files/user-functions.php";
                    $user = getUserFromId($_GET['id']);
                    ?>

                    <img src="../assets/images/uploads/profile-pictures/<?php echo $user["profile_picture"] ?>"
                        alt="Profile Picture" class="profile-picture">
                    <h2 class="mt-3">
                        <?php echo $user["fname"] ?>
                    </h2>
                    <p class="text-muted">
                        <?php echo "@" . $user["uname"] . " • " . $user["age"]; ?>
                    </p>
                    <p>
                        <?php echo $user["bio"]; ?>
                    </p>
                    <div class="d-flex justify-content-around">
                        <div class="followers-count">
                            <strong>Followers</strong>
                            <a href="user-followers-list.php?id=<?php echo $user["id"] ?>" onclick="removeLinkIfZero()"
                                id="followersCountLink">
                                <div class="text-center" id="followersCount">
                                    <?php
                                    echo getFollowersCount($user["id"]); ?>
                                </div>
                            </a>
                        </div>
                        <div class="following-count">
                            <strong>Following</strong>
                            <a href="user-following-list.php?id=<?php echo $user["id"] ?>" onclick="removeLinkIfZero()"
                                id="followingCountLink">
                                <div class="text-center" id="followingCount">
                                    <?php
                                    echo getFollowingCount($user["id"]); ?>
                                </div>
                            </a>
                        </div>
                    </div>

                    <?php
                    if ($user['privacy'] == "Public") {
                        if (doesFollow($_SESSION['user_id'], $user["id"])) { ?>
                            <button onclick="follow(this.innerHTML);" id="followBtn"
                                class="btn btn-block mt-3 btn-custom">Unfollow</button>
                        <?php } else { ?>
                            <button onclick="follow(this.innerHTML);" id="followBtn" ;
                                class="btn btn-block mt-3 btn-custom">Follow</button>
                        <?php }
                    } else {
                        if (!doesFollow($_SESSION['user_id'], $user["id"])) {
                            if (hasSentRequest($_SESSION['user_id'], $user["id"])) { ?>
                                <button onclick="follow(this.innerHTML);" id="followBtn"
                                    class="btn btn-block mt-3 btn-custom">Request Sent</button>
                            <?php } else { ?>
                                <button onclick="follow(this.innerHTML);" id="followBtn" class="btn btn-block mt-3 btn-custom">Send Follow Request</button>
                            <?php }
                        } else {
                            ?>
                            <button onclick="confirmUnfollow(this.innerHTML);" id="followBtn"
                                class="btn btn-block mt-3 btn-custom">Unfollow</button>
                            <?php
                        }
                    } ?>

                </div>
            </div>
            <div class="col-md-9 posts-container" id="postContainer">
                <?php
                if ($user["privacy"] != "Public") {
                    if (!doesFollow($_SESSION["user_id"], $user["id"])) {
                        echo '<div class="text-center d-flex justify-content-center align-items-center" style="height: 60vh;">';
                        echo '<div class="d-flex justify-content-center flex-column align-items-center" style="height:60vh;">';
                        echo '<img src="../assets/images/privateicon.png" alt="Private Account" class="img-fluid" style="height:200px; width:200px">';
                        echo '<h5 class="mt-3">This is a private account. Follow to see the posts.</h5>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        include "../function-files/post-functions.php";
                        include "../function-files/like-functions.php";
                        include "../function-files/comment-functions.php";
                        // Fetch and display user's posts from the database
                        // Modify the code based on your database structure and retrieval method
                
                        $userPosts = getPosts($user["id"]);
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
                                    echo '</button>';
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
                            echo '<img src="../assets/images/nopost.png" alt="No Posts" class="img-fluid" style="height: 200px; width: 200px;">';
                            echo '<h5 class="mt-3">User has not posted anything yet.</h5>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } else {
                    include "../function-files/post-functions.php";
                    include "../function-files/like-functions.php";
                    include "../function-files/comment-functions.php";
                    // Fetch and display user's posts from the database
                    // Modify the code based on your database structure and retrieval method
                
                    $userPosts = getPosts($user["id"]);
                    if ($userPosts->num_rows > 0) {
                        echo '<div class="posts-container">';
                        echo '<div class="row">';
                        while ($post = $userPosts->fetch_assoc()) {

                            echo '<div class="col-md-4">';
                            echo '<div class="post" title="Click on the post to interact" ' . $post["id"] . ' >';
                            echo '<div onclick="viewPost(' . $post["id"] . ')">';
                            echo '<img src="../assets/images/uploads/posts/' . $post['image_name'] . '" class="img-fluid post-img">';
                            echo '<hr>'; // Line separator
                            echo '<p>' . $post['caption'] . '</p>';
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
                                echo '</button>';
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
                        echo '<img src="../assets/images/nopost.png" alt="No Posts" class="img-fluid" style="height: 200px; width: 200px;">';
                        echo '<h5 class="mt-3">User has not posted anything yet.</h5>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/js/user-view-profile.js"></script>

</body>

</html>