// Get the file input element and preview container
const imageInput = document.getElementById("image");
const imagePreview = document.getElementById("image-preview");

// Add an event listener to the file input
imageInput.addEventListener("change", function (event) {
  const file = event.target.files[0]; // Get the selected file

  if (file) {
    const reader = new FileReader();

    // Read the file and set the image source once it's loaded
    reader.addEventListener("load", function () {
      const image = new Image();
      image.src = reader.result;
      image.style.width = "200px"; // Set maximum width
      image.style.height = "200px"; // Set maximum height
      image.style.objectFit = "cover";

      image.style.border = "1px solid #2b7a78";
      image.style.borderRadius = "50%";
      // Append the image to the preview container
      imagePreview.innerHTML = "";
      imagePreview.appendChild(image);
      imagePreview.style.display = "block";
    });

    // Read the file as a data URL
    reader.readAsDataURL(file);
  }
});

function passwordSec() {
  if (document.getElementById("password").style.display == "block") {
    document.getElementById("password").style.display = "none";
    document
      .getElementById("picon")
      .classList.remove("fa-sharp", "fa-solid", "fa-xmark");
    document
      .getElementById("picon")
      .classList.add("fa-solid", "fa-pen-to-square");
    document.getElementById("cpassword").required = false;
    document.getElementById("ccpassword").required = false;
    document.getElementById("npassword").required = false;
    document.getElementById("ccpassword").value =
      document.getElementById("old-passhash").value;
  } else {
    document.getElementById("password").style.display = "block";
    document
      .getElementById("picon")
      .classList.remove("fa-solid", "fa-pen-to-square");
    document
      .getElementById("picon")
      .classList.add("fa-sharp", "fa-solid", "fa-xmark");
    document.getElementById("cpassword").required = true;
    document.getElementById("ccpassword").required = true;
    document.getElementById("npassword").required = true;
    document.getElementById("ccpassword").value = "";
  }
}

var validnum = [document.getElementById("phone").value];
function phoneChange() {
  var phone = document.getElementById("phone");
  console.log(validnum);

  for (var i = 0; i < validnum.length; i++) {
    if (phone.value == validnum[i]) {
      document.getElementById("updatebtn").disabled = false;
      document.getElementById("updatebtn").title = "";
      document.getElementById("sendotp").style.display = "none";
      document.getElementById("recaptcha-container").style.display = "none";
      document.getElementsByClassName("p-conf")[0].style.display = "block";
      return true;
    }
  }
  document.getElementById("updatebtn").disabled = true;
  document.getElementById("updatebtn").title =
    "Verify number before updating profile";
  document.getElementById("sendotp").style.display = "block";
  document.getElementById("recaptcha-container").style.display = "block";
  document.getElementsByClassName("p-conf")[0].style.display = "none";
}



window.addEventListener("load", phoneChange);
