import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import {
  createUserWithEmailAndPassword,
  signInWithEmailAndPassword
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import {
  setDoc,
  doc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { app, auth, db } from '../firebase.js';

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
    window.location.href = "../views/layouts/studentView/application-list.html"; // or wherever
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

    const userDoc = await (await import("https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js")).getDoc(
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
