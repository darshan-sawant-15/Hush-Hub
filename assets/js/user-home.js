window.onload = function () {
  givePostList();
};

// function openComment(postId) {
//         var commentSec = document.getElementById(postId).querySelector("#comment-section");
//         commentSec.style.display = "block";
// }

function givePostList() {
  var xmlhttpPosts = new XMLHttpRequest();
  document.getElementById("postContainer").innerHTML =
    document.getElementById("postContainer").innerHTML +
    '<div class="loader-container"><div class="loader"></div></div>';
  xmlhttpPosts.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("postContainer").innerHTML = this.responseText;
    }
  };
  xmlhttpPosts.open(
    "GET",
    "../function-files/post-functions.php?action=givePostList",
    true
  );
  xmlhttpPosts.send();
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
          //if like count is disabled
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
          //if like count is disabled
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
            '<i class="fa-sharp fa-solid fa-heart" style="color: white; margin-right:10px;"></i> <span id="like-count" onclick="showLikers(' +
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
