import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-auth.js";
import { getFirestore, doc, getDoc } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyCdzaCYqLgUGhsSKUsG2bVHIgeLq7AFWOQ",
  authDomain: "oxfordform2.firebaseapp.com",
  projectId: "oxfordform2",
  storageBucket: "oxfordform2.appspot.com",
  messagingSenderId: "570492980550",
  appId: "1:570492980550:web:dfbc14f380cd7fcf4d6947"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);

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
        window.location.href = "admin-dashboard.html";
        break;
      case "reviewer":
        window.location.href = "reviewer-dashboard.html";
        break;
      case "student":
        window.location.href = "student-dashboard.html";
        break;
      default:
        throw new Error("Unknown user role.");
    }

  } catch (err) {
    errorMsg.textContent = err.message;
    console.error("Login error:", err);
  }
});
