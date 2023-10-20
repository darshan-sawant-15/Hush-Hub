window.onload = function () {
  loadMessagersList();
};

function showChatBox() {
  document.getElementById("chat-room").style.display = "";
}

function loadMessagersList() {
  var xmlhttpGetMessengers = new XMLHttpRequest();
  console.log("reaching");
  xmlhttpGetMessengers.onreadystatechange = function () {
    console.log("reaching");
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("messengers-container").innerHTML =
        this.responseText;
      console.log(this.responseText);
      if (document.querySelector("#messengers-container .messenger"))
        sortDivs();
    }
  };
  xmlhttpGetMessengers.open(
    "GET",
    "../function-files/user-functions.php?action=giveMessengers",
    true
  );
  xmlhttpGetMessengers.send();
}

var messagesLoaded = false;

function messengerSelected(userId) {
  openMessages(userId);
}

function applyBorder(userId) {
  var messenger = document.getElementById(userId);
  //removing border from all
  var elements = document.querySelectorAll(".messenger");
  elements.forEach(function (element) {
    element.style.border = "none";
  });
  //adding border to One
  messenger.style.border = "2px solid #17252A";
}

function removeStrong(userId) {
  var userContainer = document.getElementById(userId);
  var strong = userContainer.querySelector("#latestMsg strong");
  if (strong) {
    var strongContent = strong.innerHTML;
    var spanID = userContainer.querySelector("#latestMsg span").id;
    userContainer.querySelector("#latestMsg").innerHTML =
      "<span id=" + spanID + "></span>" + strongContent;
  }
}

function openMessages(userId) {
  var xmlhttpGetMessages = new XMLHttpRequest();
  //for mobile devices
  //updating message container with loader

  //loader
  document.getElementById("message-container").innerHTML =
    '<input type="hidden" id="receiver" value="0" />' +
    '<input type = "hidden" id = "messageCount" value = "0" /> <div class="loader-container"><div class="loader"></div></div>';
  showChatBox();

  if ($(window).width() < 768) {
    $(".navbar-brand i").css("display", "");
     $(".messengers").css("display","none");
  }
  else{
    applyBorder(userId);
  }
  removeStrong(userId);

  xmlhttpGetMessages.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("message-container").innerHTML =
        this.responseText;

      // if (!messagesLoaded) {
      var chatbox = document.getElementById("message-container");
      chatbox.scrollTop = chatbox.scrollHeight;
      messagesLoaded = true;

      //}
    }
  };
  var senderId = document.getElementById("sender_id").value;
  xmlhttpGetMessages.open(
    "GET",
    "../function-files/message-functions.php?action=giveMessageUI&sender_id=" +
      senderId +
      "&receiver_id=" +
      userId,
    true
  );
  xmlhttpGetMessages.send();
}

function sendMessage() {
  var messageText = document.getElementById("messageToSend").value;
  if(messageText===""){
    alert("Type something before sending the message");
    return;
  }
  // console.log(messageText);
  var receiverId = document.getElementById("receiver").value;
  var xmlhttpSendMessages = new XMLHttpRequest();
  xmlhttpSendMessages.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      // var lastMessageId = document.getElementsByClassName("lastMessage")[0].id;
      // appendNewMessage(<?= $_SESSION["user_id"] ?>, receiverId, lastMessageId);
      // console.log(this.responseText);
      document.getElementById("messageToSend").value = "";
      // console.log(this.response);
    }
  };
  var senderId = document.getElementById("sender_id").value;
  xmlhttpSendMessages.open(
    "GET",
    "../function-files/message-functions.php?action=message&sender_id=" +
      senderId +
      "&receiver_id=" +
      receiverId +
      "&messageText=" +
      encodeURIComponent(messageText),
    true
  );
  xmlhttpSendMessages.send();
}

//appending new msgs
const startInterval = setInterval(updateMessages, 1000);

function updateMessages() {
  // console.log("Inside updateMessages");
  var senderId = document.getElementById("sender_id").value;
  var receiverId = document.getElementById("receiver").value;
  if (receiverId != 0) {
    // console.log("Inside If");
    var lastMessageId = document.getElementsByClassName("lastMessage")[0].id;
    checkIfNewMessage(senderId, receiverId, lastMessageId);
  }
}

function checkIfNewMessage(senderId, receiverId, lastMessageId) {
  var xmlhttpCheckMessage = new XMLHttpRequest();
  xmlhttpCheckMessage.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "Yes") {
        appendNewMessage(senderId, receiverId, lastMessageId);
      }
    }
  };
  xmlhttpCheckMessage.open(
    "GET",
    "../function-files/message-functions.php?action=checkIfNewMessage&sender_id=" +
      senderId +
      "&receiver_id=" +
      receiverId +
      "&lastMessageId=" +
      lastMessageId,
    true
  );
  xmlhttpCheckMessage.send();
}

function appendNewMessage(senderId, receiverId, lastMessageId) {
  var xmlhttpCheckMessage = new XMLHttpRequest();
  xmlhttpCheckMessage.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var secondLastMessage = document.getElementsByClassName("lastMessage")[0];
      secondLastMessage.classList.remove("lastMessage");
      if (lastMessageId != 0) {
        var PrevMessages =
          document.getElementById("message-container").innerHTML;
        document.getElementById("message-container").innerHTML =
          PrevMessages + this.responseText;
      } else {
        document.getElementById("message-container").innerHTML =
          this.responseText;
      }
      var chatbox = document.getElementById("message-container");
      chatbox.scrollTop = chatbox.scrollHeight;
      messagesLoaded = true;
      removeStrong(document.getElementById("receiver").value);
    }
  };
  xmlhttpCheckMessage.open(
    "GET",
    "../function-files/message-functions.php?action=appendNewMessage&sender_id=" +
      senderId +
      "&receiver_id=" +
      receiverId +
      "&lastMessageId=" +
      lastMessageId,
    true
  );
  xmlhttpCheckMessage.send();
}

//for updating message on the card
const startInterval2 = setInterval(getOverallLastMessage, 500);

function getOverallLastMessage() {
  var currentLastMsgId = document.getElementById("olm_id").value;
  var user_id = document.getElementById("sender_id").value;
  var xmlhttpOLM = new XMLHttpRequest();
  xmlhttpOLM.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var response = JSON.parse(this.responseText);
      if (response.id != currentLastMsgId) {
        document.getElementById("olm_id").value = response.id;
        if (user_id == response.sender_id) {
          const userContainer = document.getElementById(response.receiver_id);
          const msgContainer = userContainer.querySelector("#latestMsg");
          msgContainer.innerHTML =
            "<span id=" + response.id + " ></span> You: " + response.message;
        } else {
          const userContainer = document.getElementById(response.sender_id);
          const msgContainer = userContainer.querySelector("#latestMsg");
          const uname = userContainer.querySelector("#uname").value;
          msgContainer.innerHTML =
            "<span id=" +
            response.id +
            " ></span> <strong>" +
            uname +
            ": " +
            response.message +
            "</strong>";
          sortDivs();
        }
      }
    }
  };
  xmlhttpOLM.open(
    "GET",
    "../function-files/message-functions.php?action=getOverallLastMessage&user_id=" +
      user_id,
    true
  );
  xmlhttpOLM.setRequestHeader("Content-Type", "application/json");
  xmlhttpOLM.send();
}

//sorting messengers
function sortDivs() {
  const messengerContainer = document.querySelector("#messengers-container");
  const messengerDivs = Array.from(
    messengerContainer.querySelectorAll(".messenger")
  );

  // Sort messageDivs in descending order based on the id of the child p element
  // console.log(messengerDivs[0].querySelector("#latestMsg span").id);
  messengerDivs.sort((a, b) => {
    // console.log(a.innerHTML);
    const idA = parseInt(a.querySelector("#latestMsg span").id);
    const idB = parseInt(b.querySelector("#latestMsg span").id);
    return idB - idA; // Reverse the order of comparison
  });

  // Clear the container and append the sorted messageDivs
  messengerContainer.innerHTML = "";
  messengerDivs.forEach((div) => messengerContainer.appendChild(div));
}

const toggleMsgList = () => {
  var windowWidth = $(window).width();

  // Check if window width is below 768 pixels
//   if (windowWidth < 768) {
  $(".messengers").css("display", "block");
      $(".navbar-brand i").css("display", "none");
      document.getElementById("message-container").innerHTML = "";
//   } 
};

//const hideMsgList = () => {
  //var windowWidth = $(window).width();
  //if (windowWidth < 768) {
    //if ($(".messengers").is(":visible")) {
      //$(".messengers").css("display", "none");
      // $(".navbar-brand i").removeClass("fa-angle-left");
      // $(".navbar-brand i").addClass("fa-angle-right");
    //}
  //}
//};

$(document).ready(function () {
  // Check window width on page load
//   toggleMsgList();

  // Check window width on window resize
//   $(window).resize(function () {
//     toggleMsgList();
//   });
});
