//checking password matching
var unameInput = document.getElementById("uname");

function validate() {
  var pass1 = document.getElementById("password");
  var pass2 = document.getElementById("cpassword");

  if (pass1.value != pass2.value) {
    pass2.focus();
    alert("Passwords don't match");
    return false;
  }

  if (unameInput.classList.contains("is-invalid")) {
    unameInput.focus();
    return false;
  }
  return true;
}


function checkUsername() {
  var username = unameInput.value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      if (this.responseText == "1") {
        unameInput.classList.add("is-invalid");
      } else {
        if (
          unameInput.classList.contains("is-invalid")
        ) {
          unameInput.classList.remove("is-invalid");
        }
      }
    }
  };
  xmlhttp.open(
    "GET",
    "function-files/user-functions.php?action=checkUsername&uname=" +
      username,
    true
  );
  xmlhttp.send();
}

// Add an "onchange" event listener
unameInput.addEventListener("input", checkUsername);


function togglePasswordVisibility(checkbox) {
  var passwordInput;
  if (checkbox.id === "showPassword") {
    passwordInput = document.getElementById("password");
    console.log("Reaching");
  } else if (checkbox.id === "showCPassword") {
    passwordInput = document.getElementById("cpassword");
  }

  if (checkbox.checked) {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}

