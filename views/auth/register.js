// Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-auth.js";
import { getFirestore, doc, setDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore.js";

// Your Firebase config (✅ make sure it's correct!)
const firebaseConfig = {
  apiKey: "AIzaSyCdzaCYqLgUGhsSKUsG2bVHIgeLq7AFWOQ",
  authDomain: "oxfordform2.firebaseapp.com",
  projectId: "oxfordform2",
  storageBucket: "oxfordform2.appspot.com",
  messagingSenderId: "570492980550",
  appId: "1:570492980550:web:dfbc14f380cd7fcf4d6947",
  measurementId: "G-P3JLGDW0SN"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);

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

    alert("Account created successfully!");
    window.location.href = "../layouts/dashboard.php";
  } catch (error) {
    console.error("Registration error:", error);
    alert("Error: " + error.message);
  }
});
