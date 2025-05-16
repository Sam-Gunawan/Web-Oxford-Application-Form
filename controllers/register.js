// Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { doc, setDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { app, auth, db } from '../firebase.js';

// Register user
document.getElementById("registerForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const role = document.getElementById("role").value;

  if (!name || !email || !password || !role) {
    alert("Please fill out all fields.");
    return;
  }

  try {
    // Create user in Firebase Auth
    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
    const uid = userCredential.user.uid;

    // Add user to Firestore
    await setDoc(doc(db, "users", uid), {
      uid,
      name,
      email,
      role,
      created_at: serverTimestamp()
    });
	localStorage.setItem("user", JSON.stringify({
      uid: uid,
      name: name,
      role: role
    }));
    alert("Account created successfully!");
    window.location.href = "../layouts/studentView/application-list.html"; // Redirect to application list page
  } catch (error) {
    console.error("Registration error:", error);
    alert("Error: " + error.message);
  }
});
