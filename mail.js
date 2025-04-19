
// Import the functions you need from the SDKs you need
// import { initializeApp } from "firebase/app";
// import { getAnalytics } from "firebase/analytics";
// import { getDatabase, ref, push, set } from "firebase/database"
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional

// Initialize Firebase
// const app = initializeApp(firebaseConfig);
// const analytics = getAnalytics(app);



const firebaseConfig = {
  apiKey: "AIzaSyCdzaCYqLgUGhsSKUsG2bVHIgeLq7AFWOQ",
  authDomain: "oxfordform2.firebaseapp.com",
  databaseURL: "https://oxfordform2-default-rtdb.firebaseio.com",
  projectId: "oxfordform2",
  storageBucket: "oxfordform2.firebasestorage.app",
  messagingSenderId: "570492980550",
  appId: "1:570492980550:web:dfbc14f380cd7fcf4d6947",
  measurementId: "G-P3JLGDW0SN"
};


// initialize firebase
firebase.initializeApp(firebaseConfig);

// reference your database
var OxfordFormDB = firebase.database().ref("OxfordForm");

document.getElementById("personal_detail").addEventListener("submit", submitForm);

function submitForm(e) {
  e.preventDefault();

  var givenName = getElementVal("givenName");
  var preferredName = getElementVal("preferredName");
  var middleNames = getElementVal("middleNames");
  var familyName = getElementVal("familyName");

  saveMessages(givenName, preferredName, middleNames, familyName);

  // //   enable alert
  // document.querySelector(".alert").style.display = "block";

  // //   remove the alert
  // setTimeout(() => {
  //   document.querySelector(".alert").style.display = "none";
  // }, 3000);

  //   reset the form
  document.getElementById("personal_detail").reset();
}

const saveMessages = (givenName, preferredName, middleNames, familyName) => {
  var newContactForm = OxfordFormDB.push();

  newContactForm.set({
    Name: givenName,
    PreferredName: preferredName,
    MiddleNames: middleNames,
    FamilyName: familyName,
  });
};

const getElementVal = (id) => {
  return document.getElementById(id).value;
};













// const firebaseConfig = {
//   apiKey: "AIzaSyCdzaCYqLgUGhsSKUsG2bVHIgeLq7AFWOQ",
//   authDomain: "oxfordform2.firebaseapp.com",
//   databaseURL: "https://oxfordform2-default-rtdb.firebaseio.com",
//   projectId: "oxfordform2",
//   storageBucket: "oxfordform2.firebasestorage.app",
//   messagingSenderId: "570492980550",
//   appId: "1:570492980550:web:dfbc14f380cd7fcf4d6947",
//   measurementId: "G-P3JLGDW0SN"
// };


// // initialize firebase
// firebase.initializeApp(firebaseConfig);

// // reference your database
// var contactFormDB = firebase.database().ref("contactForm");

// document.getElementById("contactForm").addEventListener("submit", submitForm);

// function submitForm(e) {
//   e.preventDefault();

//   var name = getElementVal("name");
//   var emailid = getElementVal("emailid");
//   var msgContent = getElementVal("msgContent");

//   saveMessages(name, emailid, msgContent);

//   //   enable alert
//   document.querySelector(".alert").style.display = "block";

//   //   remove the alert
//   setTimeout(() => {
//     document.querySelector(".alert").style.display = "none";
//   }, 3000);

//   //   reset the form
//   document.getElementById("contactForm").reset();
// }

// const saveMessages = (name, emailid, msgContent) => {
//   var newContactForm = contactFormDB.push();

//   newContactForm.set({
//     name: name,
//     emailid: emailid,
//     msgContent: msgContent,
//   });
// };

// const getElementVal = (id) => {
//   return document.getElementById(id).value;
// };
