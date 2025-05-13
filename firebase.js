// /js/firebase.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { getAuth, GoogleAuthProvider, createUserWithEmailAndPassword, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

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

// const firebaseConfig = {
//   apiKey: "AIzaSyB1ZErQBrQ3wZrX4seBSKFhQvlAzPiHk1E",
//   authDomain: "oxfordweb-local.firebaseapp.com",
//   projectId: "oxfordweb-local",
//   storageBucket: "oxfordweb-local.firebasestorage.app",
//   messagingSenderId: "502474430288",
//   appId: "1:502474430288:web:6e9dcd638677f0adb6be55",
//   measurementId: "G-YV7FL9Q735"
// };

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const auth = getAuth(app);

export { db, auth, GoogleAuthProvider, createUserWithEmailAndPassword, signInWithEmailAndPassword }

