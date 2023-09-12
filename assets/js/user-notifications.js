var userId = document.getElementById("user_id").value;
var userPrivacy = document.getElementById("user_privacy").value;



function getFollowRequests() {
  var followBtn = document.getElementById("followBtn");
  followBtn.style.backgroundColor = "#17252A";
  followBtn.style.color = "#DEF2F1";
  var notBtn = document.getElementById("notBtn");
  notBtn.style.backgroundColor = "#def2f1";
  notBtn.style.color = "#17252A";
  var xmlhttpGetUsers = new XMLHttpRequest();
  xmlhttpGetUsers.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("usersContainer").innerHTML = this.responseText;
      var xmlhttpNotCount = new XMLHttpRequest();
      xmlhttpNotCount.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("notText").innerHTML =
            "Notifications (" + this.responseText + ")";
        }
      };
      xmlhttpNotCount.open(
        "GET",
        "../function-files/notification-functions.php?action=getNotificationCount&id=" +
          userId,
        true
      );
      xmlhttpNotCount.send();

      var xmlhttpReqCount = new XMLHttpRequest();
      xmlhttpReqCount.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("reqText").innerHTML =
            "Follow Requests (" + this.responseText + ")";
        }
      };
      xmlhttpReqCount.open(
        "GET",
        "../function-files/follow-functions.php?action=getRequestCount&id=" + userId,
        true
      );
      xmlhttpReqCount.send();
    }
  };
  xmlhttpGetUsers.open(
    "GET",
    "../function-files/user-functions.php?action=giveReqUsers",
    true
  );
  xmlhttpGetUsers.send();
}

function acceptRequest(followerId, followingId) {
  var xmlhttpAccept = new XMLHttpRequest();
  xmlhttpAccept.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      getFollowRequests();
      alert("Follow Request Accepted");
      var xmlhttpNotCount = new XMLHttpRequest();
      xmlhttpNotCount.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("notText").innerHTML =
            "Notifications (" + this.responseText + ")";
        }
      };
      xmlhttpNotCount.open(
        "GET",
        "../function-files/notification-functions.php?action=getNotificationCount&id=" +
          userId,
        true
      );
      xmlhttpNotCount.send();

      var xmlhttpReqCount = new XMLHttpRequest();
      xmlhttpReqCount.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("reqText").innerHTML =
            "Follow Requests (" + this.responseText + ")";
        }
      };
      xmlhttpReqCount.open(
        "GET",
        "../function-files/follow-functions.php?action=getRequestCount&id=" + userId,
        true
      );
      xmlhttpReqCount.send();
    }
  };
  xmlhttpAccept.open(
    "GET",
    "../function-files/follow-functions.php?action=acceptRequest&followerId=" +
      followerId +
      "&followingId=" +
      followingId,
    true
  );
  xmlhttpAccept.send();
}

function rejectRequest(followerId, followingId) {
  var xmlhttpRequest = new XMLHttpRequest();
  xmlhttpRequest.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      getFollowRequests();
      var xmlhttpNotCount = new XMLHttpRequest();
      xmlhttpNotCount.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("notText").innerHTML =
            "Notifications (" + this.responseText + ")";
        }
      };
      xmlhttpNotCount.open(
        "GET",
        "../function-files/notification-functions.php?action=getNotificationCount&id=" +
          userId,
        true
      );
      xmlhttpNotCount.send();

      var xmlhttpReqCount = new XMLHttpRequest();
      xmlhttpReqCount.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("reqText").innerHTML =
            "Follow Requests (" + this.responseText + ")";
        }
      };
      xmlhttpReqCount.open(
        "GET",
        "../function-files/follow-functions.php?action=getRequestCount&id=" + userId,
        true
      );
      xmlhttpReqCount.send();
    }
  };
  xmlhttpRequest.open(
    "GET",
    "../function-files/follow-functions.php?action=unfollow&followerId=" +
      followerId +
      "&followingId=" +
      followingId,
    true
  );
  xmlhttpRequest.send();
}

function getNotifications() {
  if (userPrivacy == "Private") {
    var notBtn = document.getElementById("notBtn");
    notBtn.style.backgroundColor = "#17252A";
    notBtn.style.color = "#DEF2F1";
    var followBtn = document.getElementById("followBtn");
    followBtn.style.backgroundColor = "#def2f1";
    followBtn.style.color = "#17252A";
  }

  var xmlhttpGetNotif = new XMLHttpRequest();
  xmlhttpGetNotif.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("usersContainer").innerHTML = this.responseText;
      if (userPrivacy == "Private") {
        var xmlhttpNotCount = new XMLHttpRequest();
        xmlhttpNotCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("notText").innerHTML =
              "Notifications (" + this.responseText + ")";
          }
        };
        xmlhttpNotCount.open(
          "GET",
          "../function-files/notification-functions.php?action=getNotificationCount&id=" +
            userId,
          true
        );
        xmlhttpNotCount.send();

        var xmlhttpReqCount = new XMLHttpRequest();
        xmlhttpReqCount.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("reqText").innerHTML =
              "Follow Requests (" + this.responseText + ")";
          }
        };
        xmlhttpReqCount.open(
          "GET",
          "../function-files/follow-functions.php?action=getRequestCount&id=" + userId,
          true
        );
        xmlhttpReqCount.send();
      }
    }
  };
  xmlhttpGetNotif.open(
    "GET",
    "../function-files/notification-functions.php?action=giveNotifications",
    true
  );
  xmlhttpGetNotif.send();
}
