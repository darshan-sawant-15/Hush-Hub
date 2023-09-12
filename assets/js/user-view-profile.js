const userId = document.getElementById("user_id").value;
const profileId = document.getElementById("profile_id").value;

function follow(str) {
  if (str == "Follow") {
    var xmlhttpFollow = new XMLHttpRequest();
    xmlhttpFollow.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("followBtn").innerHTML = this.responseText;

        var xmlhttpFollowerCount = new XMLHttpRequest();
        xmlhttpFollowerCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("followersCount").innerHTML = this.response;
          }
        };
        xmlhttpFollowerCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getFollowersCount&id=" +
            profileId,
          true
        );
        xmlhttpFollowerCount.send();

        var xmlhttpFollowingCount = new XMLHttpRequest();
        xmlhttpFollowingCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("followingCount").innerHTML = this.response;
          }
        };
        xmlhttpFollowingCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getFollowingCount&id=" +
            profileId,
          true
        );
        xmlhttpFollowingCount.send();
      }
    };
    xmlhttpFollow.open(
      "GET",
      "../function-files/follow-functions.php?action=follow&followerId=" +
        userId +
        "&followingId=" +
        profileId,
      true
    );
    xmlhttpFollow.send();
  } else if (str == "Send Follow Request") {
    var xmlhttpFollowReq = new XMLHttpRequest();
    xmlhttpFollowReq.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("followBtn").innerHTML = this.responseText;
      }
    };
    xmlhttpFollowReq.open(
      "GET",
      "../function-files/follow-functions.php?action=followPrivate&followerId=" +
        userId +
        "&followingId=" +
        profileId,
      true
    );
    xmlhttpFollowReq.send();
  } else if (str == "Request Sent") {
    var xmlhttpFollowReq = new XMLHttpRequest();
    xmlhttpFollowReq.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("followBtn").innerHTML = "Send Follow Request";
      }
    };
    xmlhttpFollowReq.open(
      "GET",
      "../function-files/follow-functions.php?action=unfollow&followerId=" +
        userId +
        "&followingId=" +
        profileId,
      true
    );
    xmlhttpFollowReq.send();
  } else if (str == "Unfollow") {
    var xmlhttpFollow = new XMLHttpRequest();
    xmlhttpFollow.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("followBtn").innerHTML = this.responseText;

        var xmlhttpFollowerCount = new XMLHttpRequest();
        xmlhttpFollowerCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("followersCount").innerHTML = this.response;
          }
        };
        xmlhttpFollowerCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getFollowersCount&id=" +
            profileId,
          true
        );
        xmlhttpFollowerCount.send();

        var xmlhttpFollowingCount = new XMLHttpRequest();
        xmlhttpFollowingCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("followingCount").innerHTML = this.response;
          }
        };
        xmlhttpFollowingCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getFollowingCount&id=" +
            profileId,
          true
        );
        xmlhttpFollowingCount.send();
      }
    };

    xmlhttpFollow.open(
      "GET",
      "../function-files/follow-functions.php?action=unfollow&followerId=" +
        userId +
        "&followingId=" +
        profileId,
      true
    );
    xmlhttpFollow.send();
  } else if (str == "UnfollowP") {
    var xmlhttpFollow = new XMLHttpRequest();
    xmlhttpFollow.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("followBtn").innerHTML = "Send Follow Request";

        var xmlhttpFollowerCount = new XMLHttpRequest();
        xmlhttpFollowerCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("followersCount").innerHTML = this.response;
          }
        };
        xmlhttpFollowerCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getFollowersCount&id=" +
            profileId,
          true
        );
        xmlhttpFollowerCount.send();

        var xmlhttpFollowingCount = new XMLHttpRequest();
        xmlhttpFollowingCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("followingCount").innerHTML = this.response;
          }
        };
        xmlhttpFollowingCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getFollowingCount&id=" +
            profileId,
          true
        );
        xmlhttpFollowingCount.send();

        document.getElementById("postContainer").innerHTML =
          '<div class="text-center d-flex justify-content-center align-items-center" style="height: 100%; padding-bottom: 200px;">' +
          '<div class="d-flex justify-content-center flex-column align-items-center" style="height:100%;">' +
          '<img src="../assets/images/privateicon.png" alt="Private Account" class="img-fluid" style="height: 200px; width: 200px;">' +
          '<h5 class="mt-3">This is a private account. Follow to see the posts.</h5>' +
          "</div>" +
          "</div>";
      }
    };

    xmlhttpFollow.open(
      "GET",
      "../function-files/follow-functions.php?action=unfollow&followerId=" +
        userId +
        "&followingId=" +
        profileId,
      true
    );
    xmlhttpFollow.send();
  }
}

window.addEventListener("DOMContentLoaded", function () {
  removeLinkIfZero();
});

function removeLinkIfZero() {
  if (
    document.getElementById("followersCount").innerHTML.trim() === "0" ||
    document.getElementById("followBtn").innerHTML == "Request Sent" ||
    document.getElementById("followBtn").innerHTML == "Send Follow Request"
  ) {
    document.getElementById("followersCountLink").removeAttribute("href");
  }
  if (
    document.getElementById("followersCount").innerHTML.trim() === "0" ||
    document.getElementById("followBtn").innerHTML == "Request Sent" ||
    document.getElementById("followBtn").innerHTML == "Send Follow Request"
  ) {
    document.getElementById("followingCountLink").removeAttribute("href");
  }
}

function viewPost(postId) {
  window.location.href = "user-view-post.php?id=" + postId;
}

function confirmUnfollow(str) {
  // Display the confirmation box
  if (str == "Unfollow") {
    var result = confirm("Are you sure you want to unfollow this account?");

    // Check the user's choice
    if (result) {
      // User clicked "OK" or "Yes"
      console.log(str + "P");
      follow(str + "P");
      // Add your action here
    }
  } else {
    follow(str);
  }
}

function like(postId) {
  var xmlhttpLike = new XMLHttpRequest();
  xmlhttpLike.onreadystatechange = function () {
    var post = document.getElementById(postId);
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "Liked") {
        if (post.querySelector("#like-count")) {
          getLikeCount(postId, true);
        } else {
          post.querySelector("#like-btn").innerHTML =
            '<i class="fa-sharp fa-solid fa-heart" style="color: red; margin-right:10px;"></i> ';
          post.querySelector("#like-btn").onclick = function () {
            unlike(postId);
          };
        }
      }
    }
  };
  xmlhttpLike.open(
    "GET",
    "../function-files/like-functions.php?action=like&postId=" + postId,
    true
  );
  xmlhttpLike.send();
}

function unlike(postId) {
  var xmlhttpDislike = new XMLHttpRequest();
  xmlhttpDislike.onreadystatechange = function () {
    var post = document.getElementById(postId);
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "Unliked") {
        if (post.querySelector("#like-count")) {
          getLikeCount(postId, false);
        } else {
          post.querySelector("#like-btn").innerHTML =
            '<i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;"></i> ';
          post.querySelector("#like-btn").onclick = function () {
            like(postId);
          };
        }
      }
    }
  };
  xmlhttpDislike.open(
    "GET",
    "../function-files/like-functions.php?action=unlike&postId=" + postId,
    true
  );
  xmlhttpDislike.send();
}

function getLikeCount(postId, liked) {
  var xmlhttpLikeCount = new XMLHttpRequest();
  xmlhttpLikeCount.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var post = document.getElementById(postId);
      if (liked) {
        if (this.responseText != "0") {
          post.querySelector("#like-btn").innerHTML =
            '<i class="fa-sharp fa-solid fa-heart" style="color: red; margin-right:10px;"></i> <span id="like-count" onclick="showLikers(' +
            postId +
            ');">' +
            this.responseText +
            "</span>";
        } else {
          post.querySelector("#like-btn").innerHTML =
            '<i class="fa-sharp fa-solid fa-heart" style="color: red; margin-right:10px;"></i> <span id="like-count">' +
            this.responseText +
            "</span>";
        }
        post.querySelector("#like-btn i").onclick = function () {
          unlike(postId);
        };
      } else {
        if (this.responseText != "0") {
          post.querySelector("#like-btn").innerHTML =
            '<i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;"></i> <span id="like-count onclick="showLikers(' +
            postId +
            ');">' +
            this.responseText +
            "</span>";
        } else {
          post.querySelector("#like-btn").innerHTML =
            '<i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;"></i> <span id="like-count">' +
            this.responseText +
            "</span>";
        }
        post.querySelector("#like-btn i").onclick = function () {
          like(postId);
        };
      }
    }
  };
  xmlhttpLikeCount.open(
    "GET",
    "../function-files/like-functions.php?action=getLikeCount&postId=" + postId,
    true
  );
  xmlhttpLikeCount.send();
}

function shareContent() {
  var shareData = {
    title: "Shared Content Title",
    text: "Check out this awesome content!",
    url: window.location.href,
  };

  if (navigator.share) {
    navigator
      .share(shareData)
      .then(function () {
        console.log("Content shared successfully.");
      })
      .catch(function (error) {
        console.error("Error sharing content:", error);
      });
  } else {
    console.log("Web Share API not supported.");
    // Fallback code for browsers that do not support Web Share API
  }
}

function showLikers(postId) {
  window.location.href = "user-likers-list.php?id=" + postId;
}
