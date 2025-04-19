// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional


// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);


const firebaseConfig = {
  apiKey: "AIzaSyDsBMcyZ_kYQBsQN6v4-w_dnASP5EPWcZY",
  authDomain: "web-oxfordform.firebaseapp.com",
  databaseURL: "https://web-oxfordform-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "web-oxfordform",
  storageBucket: "web-oxfordform.firebasestorage.app",
  messagingSenderId: "1089004501733",
  appId: "1:1089004501733:web:4cc08673c522a43bb8320c",
  measurementId: "G-9JT0EN98KD"
};

// initialize firebase
firebase.initializeApp(firebaseConfig);

// reference your database
var OxfordFormDB = firebase.database().ref("OxfordForm");

document.getElementById("contactForm").addEventListener("submit", submitForm);

function submitForm(e) {
  e.preventDefault();

  var name = getElementVal("name");
  var emailid = getElementVal("emailid");
  var msgContent = getElementVal("msgContent");

  saveMessages(name, emailid, msgContent);

  //   enable alert
  document.querySelector(".alert").style.display = "block";

  //   remove the alert
  setTimeout(() => {
    document.querySelector(".alert").style.display = "none";
  }, 3000);

  //   reset the form
  document.getElementById("contactForm").reset();
}

const saveMessages = (name, emailid, msgContent) => {
  var newContactForm = contactFormDB.push();

  newContactForm.set({
    name: name,
    emailid: emailid,
    msgContent: msgContent,
  });
};

const getElementVal = (id) => {
  return document.getElementById(id).value;
};
