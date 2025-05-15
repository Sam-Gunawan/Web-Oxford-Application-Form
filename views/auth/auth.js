import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import {
  getAuth,
  createUserWithEmailAndPassword,
  signInWithEmailAndPassword
} from "https://www.gstatic.com/firebasejs/9.22.2/firebase-auth.js";
import {
  getFirestore,
  collection,
  setDoc,
  doc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore.js";

// Firebase config
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

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);

const form = document.getElementById("authForm");
const registerBtn = document.getElementById("registerBtn");
const loginBtn = document.getElementById("loginBtn");
const roleField = document.getElementById("role");

registerBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  const email = form.email.value.trim();
  const password = form.password.value.trim();
  const role = form.role.value;

  if (!email || !password || !role) return alert("Please fill in all fields.");

  if (role !== "student") {
    return alert("Only students can register.");
  }

  try {
    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
    const uid = userCredential.user.uid;

    await setDoc(doc(db, "users", uid), {
      email,
      role,
      created_at: serverTimestamp(),
      id: uid
    });

    alert("Registration successful!");
    window.location.href = "index.html"; // or wherever
  } catch (err) {
    console.error(err);
    alert(err.message);
  }
});

loginBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  const email = form.email.value.trim();
  const password = form.password.value.trim();
  const role = form.role.value;

  if (!email || !password || !role) return alert("Please fill in all fields.");

  try {
    const userCredential = await signInWithEmailAndPassword(auth, email, password);
    const uid = userCredential.user.uid;

    const userDoc = await (await import("https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore.js")).getDoc(
      doc(db, "users", uid)
    );

    if (!userDoc.exists()) {
      throw new Error("User data not found.");
    }

    const userData = userDoc.data();

    if (userData.role !== role) {
      throw new Error("Incorrect role for this user.");
    }

    alert(`Welcome ${userData.role}!`);
    // redirect logic here (e.g., dashboard based on role)
  } catch (err) {
    console.error(err);
    alert("Login failed: " + err.message);
  }
});
