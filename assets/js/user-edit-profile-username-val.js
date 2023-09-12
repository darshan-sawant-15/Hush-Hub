function checkUsername() {
  var username = unameInput.value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "1" && username != currUname) {
        unameInput.classList.add("is-invalid");
      } else {
        if (unameInput.classList.contains("is-invalid")) {
          unameInput.classList.remove("is-invalid");
        }
      }
    }
  };
  xmlhttp.open(
    "GET",
    "../function-files/user-functions.php?action=checkUsername&uname=" +
      username,
    true
  );
  xmlhttp.send();
}

function validate() {
  if (unameInput.classList.contains("is-invalid")) {
    unameInput.focus();
    return false;
  }
  return true;
}

function removeProfilePicture() {
  var removeBtn = document.getElementById("removeBtn");
  var imagePreview = document.getElementById("image-preview");
  var imagePreviewImage = imagePreview.querySelector("img");

  imagePreviewImage.src =
    "../assets/images/uploads/profile-pictures/default.png";
  removeBtn.style.display = "none";
  document.getElementById("image-removed").value = 1;
}

function checkIfNoProfilePicture() {
  var imagePreview = document.getElementById("image-preview");
  var imagePreviewImage = imagePreview.querySelector("img");
  if (
    imagePreviewImage.src ==
    "http://localhost/SocialMediaAppArranged/assets/images/uploads/profile-pictures/default.png"
  ) {
    removeBtn.style.display = "none";
  }
}

var currUname = document.getElementById("uname").value;

unameInput = document.getElementById("uname");
unameInput.addEventListener("input", checkUsername);

window.addEventListener("load", checkIfNoProfilePicture);
