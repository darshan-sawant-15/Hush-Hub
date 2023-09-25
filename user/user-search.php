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
    <title>Search Users</title>
    <?php include "header.php" ?>
</head>


<body>
    <?php include "base-user.php" ?>
    <div>
        <div class=" row p-4">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body mb-2">
                        <h4 class="text-center mb-3" style="color: #17252A">Search Users</h4>
                        <form class="form-inline" method="get">
                            <?php $searchTerm = "";
                            if (!empty($_GET['searchTerm'])) {
                                $searchTerm = $_GET['searchTerm'];
                            } ?>
                            <div class="form-group col-md-10 custom-input-container">
                                <input type="text" placeholder="Type Username Ex. darshan26"
                                    class="form-control custom-input" name="searchTerm" maxlength="100"
                                    value="<?php echo $searchTerm; ?>">
                            </div>
                            <div class="form-group col-md-2">
                                <button class="btn btn-block btn-custom"  id="searchBtn"
                                    type="button" onclick="search()"><i class="fa-solid fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="usersContainer">
                    <?php
                    include '../function-files/user-functions.php';
                    if (!empty($_GET['searchTerm'])) {
                        $searchTerm = strtolower($searchTerm);

                        $users = getSearchedUsers($searchTerm);
                        $html = "";
                        $userCount = 0;
                        if ($users->num_rows > 0) {
                            while ($user = $users->fetch_assoc()) {
                                if ($user["id"] != $_SESSION["user_id"]) {
                                    $html .= '<div class="card mt-2">';
                                    $html .= '<div class="card-body d-flex align-items-center">';
                                    $html .= '<div class="mr-4">';
                                    $html .= '<img src="../assets/images/uploads/profile-pictures/' . $user["profile_picture"] . '" class="rounded-circle" style="border: 1px solid #17252A;"
                                    width="75" height="75" alt="Profile Picture">';
                                    $html .= '</div>';
                                    $html .= '<div>';
                                    $html .= '<h6 class="card-title">';
                                    $html .= '@' . $user["uname"] . ' • ' . $user["age"];
                                    $html .= '</h6>';
                                    $html .= '<p class="card-text">' . $user["fname"] . '</p>';
                                    $html .= '<a href="user-view-profile.php?id=' . $user['id'] . '" class="btn btn-sm btn-custom">View Profile</a>';
                                    $html .= '</div></div></div>';
                                    $userCount++;
                                }
                            }

                        }
                        if ($userCount == 0) {
                            $html .= '<div class="card mt-2">';
                            $html .= '<h5 class="text-center mt-4" style="padding-bottom:15px">No Such Active User</h5>';
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/user-search.js"></script>
</body>

</html>