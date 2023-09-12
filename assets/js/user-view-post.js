function newComment(btn) {
  document.getElementById("comment-form").style.display = "block";
  document.getElementById("new-comment").focus();
}

window.onload = function () {
  givePostUI(document.getElementById("post_id").value);
};

function givePostUI(postId) {
  var xmlhttpPosts = new XMLHttpRequest();
  xmlhttpPosts.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("postContainer").innerHTML = this.responseText;
    }
  };
  xmlhttpPosts.open(
    "GET",
    "../function-files/post-functions.php?action=givePostUI&postId=" + postId,
    true
  );
  xmlhttpPosts.send();
}

function giveCommentSection(postId) {
  var xmlhttpPosts = new XMLHttpRequest();
  xmlhttpPosts.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("comment-section").innerHTML = this.responseText;
    }
  };
  xmlhttpPosts.open(
    "GET",
    "../function-files/post-functions.php?action=giveCommentSection&postId=" +
      postId,
    true
  );
  xmlhttpPosts.send();
}

function addComment(postId) {
  var xmlhttpComment = new XMLHttpRequest();
  var comment = document.getElementById("new-comment").value;
  if(comment===""){
    alert("Type something before trying to comment");
    return;
  }
  xmlhttpComment.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "Commented") {
        giveCommentSection(postId);
      }
    }
  };
  xmlhttpComment.open(
    "GET",
    "../function-files/comment-functions.php?action=addComment&postId=" +
      postId +
      "&comment=" +
      encodeURIComponent(comment),
    true
  );
  xmlhttpComment.send();
}

function delComment(postId, commentId) {
  var xmlhttpDelComment = new XMLHttpRequest();
  var comment = document.getElementById("new-comment").value;
  xmlhttpDelComment.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "Uncommented") {
        giveCommentSection(postId);
      }
    }
  };
  xmlhttpDelComment.open(
    "GET",
    "../function-files/comment-functions.php?action=delComment&commentId=" +
      commentId,
    true
  );
  xmlhttpDelComment.send();
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

function deletePost(postId) {
  if (confirm("Are you sure you want to delete this post?")) {
    var xmlhttpDelPost = new XMLHttpRequest();
    xmlhttpDelPost.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        if (this.responseText == "Post Deleted") {
          window.location.href = "user-profile.php";
        } else {
          console.log(this.responseText);
        }
      }
    };
    xmlhttpDelPost.open(
      "GET",
      "../function-files/post-functions.php?action=delPost&postId=" + postId,
      true
    );
    xmlhttpDelPost.send();
  }
}

function showLikers(postId) {
  window.location.href = "user-likers-list.php?id=" + postId;
}

// function reportPost(postId, posterId, reporterId) {
//     if (confirm("Are you sure you want to report this post?")) {
//         var xmlhttpReportPost = new XMLHttpRequest();
//         xmlhttpReportPost.onreadystatechange = function () {
//             if (this.readyState == 4 && this.status == 200) {
//                 if (this.responseText == "Post Reported") {
//                     console.log(this.responseText);
//                     window.location.href = "feed.php";
//                 }
//             }
//         }
//         xmlhttpReportPost.open("GET", "../function-files/report-functions.php?action=reportPost&postId=" + postId + "&posterId=" + posterId + "&reporterId=" + reporterId, true);
//         xmlhttpReportPost.send();
//     }
// }

// function reportComment(commentId, commenterId, reporterId) {
//     if (confirm("Are you sure you want to report this comment?")) {
//         var xmlhttpReportComment = new XMLHttpRequest();
//         xmlhttpReportComment.onreadystatechange = function () {
//             if (this.readyState == 4 && this.status == 200) {
//                 if (this.responseText == "Comment Reported") {
//                     console.log(this.responseText);
//                     giveCommentSection();
//                 }
//             }
//         }
//         xmlhttpReportComment.open("GET", "../function-files/report-functions.php?action=reportComment&commentId=" + commentId + "&commenterId=" + commenterId + "&reporterId=" + reporterId, true);
//         xmlhttpReportComment.send();
//     }
// }
