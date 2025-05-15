import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import {signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { doc, getDoc } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { auth, db } from '../firebase.js';


document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const errorMsg = document.getElementById("errorMsg");

  try {
    const userCredential = await signInWithEmailAndPassword(auth, email, password);
    const uid = userCredential.user.uid;

    const userDoc = await getDoc(doc(db, "users", uid));
    if (!userDoc.exists()) throw new Error("User role not found in Firestore.");

    const userData = userDoc.data();
    const role = userData.role;

    // Save user info to localStorage
    localStorage.setItem("user", JSON.stringify({
      uid: uid,
      name: userData.name,
      role: role
    }));

    // 🔁 Redirect based on role
    switch (role) {
      case "admin":
        window.location.href = "../layouts/adminView/application-list.html";
        break;
      case "reviewer":
        window.location.href = "../layouts/reviewerView/application-list.html";
        break;
      case "student":
        window.location.href = "../layouts/studentView/application-list.html";
        break;
      default:
        throw new Error("Unknown user role.");
    }

  } catch (err) {
    errorMsg.textContent = err.message;
    console.error("Login error:", err);
  }
});
