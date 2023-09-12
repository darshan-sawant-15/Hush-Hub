// For Firebase JS SDK v7.20.0 and later, measurementId is optional
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-app.js";
import {
  getAuth,
  RecaptchaVerifier,
  signInWithPhoneNumber,
} from "https://www.gstatic.com/firebasejs/9.18.0/firebase-auth.js";
const firebaseConfig = {
  apiKey: "AIzaSyBrJ5HUMleKRQq7DzW5Vy12uaPHd95LHMg",
  authDomain: "otp-verification-app-56d91.firebaseapp.com",
  projectId: "otp-verification-app-56d91",
  storageBucket: "otp-verification-app-56d91.appspot.com",
  messagingSenderId: "488853124474",
  appId: "1:488853124474:web:d44937fcb49ef6bf1f12c3",
  measurementId: "G-Q12EVN4RG8",
};

const firebase = initializeApp(firebaseConfig);
const auth = getAuth();

window.recaptchaVerifier = null;

function render() {
  window.recaptchaVerifier = new RecaptchaVerifier(
    "recaptcha-container",
    {},
    auth
  );
  recaptchaVerifier.render();
}

//checking if phone number is verified
function phoneAuth() {
  var number = "+91" + document.getElementById("phone").value;
  console.log("working..");
  signInWithPhoneNumber(auth, number, window.recaptchaVerifier)
    .then((confirmationResult) => {
      window.confirmationResult = confirmationResult;
      console.log("OTP Sent");
      document.getElementById("verifier").style.display = "block";
    })
    .catch(function (error) {
      // error in sending OTP
      alert(error.message);
    });
}

//checking if code is verified
function codeverify() {
  var code = document.getElementById("otpverify").value;

  confirmationResult
    .confirm(code)
    .then(function () {
      console.log("OTP matched");
      document.getElementById("recaptcha-container").style.display = "none";
      document.getElementById("sendotp").style.display = "none";
      document.getElementById("verifier").style.display = "none";
      document.getElementsByClassName("p-conf")[0].style.display = "block";
      document.getElementById("submitbtn").disabled = false;
      document.getElementById("submitbtn").title = "";
    })
    .catch(function () {
      document.getElementsByClassName("p-conf")[0].style.display = "none";
      document.getElementsByClassName("n-conf")[0].style.display = "block";
    });
}

export { phoneAuth, codeverify, render };