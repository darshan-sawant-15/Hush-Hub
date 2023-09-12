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
      image.style.maxWidth = "100%"; // Set maximum width
      image.style.maxHeight = "300px"; // Set maximum height
      image.style.border = "1px solid #2b7a78";
      image.style.borderRadius = "2px";
      // Append the image to the preview container
      imagePreview.innerHTML = "";
      imagePreview.appendChild(image);
      imagePreview.style.display = "block";
    });

    // Read the file as a data URL
    reader.readAsDataURL(file);
  }
});
