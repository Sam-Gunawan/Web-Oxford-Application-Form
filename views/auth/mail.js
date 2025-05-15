
// Import Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getFirestore, collection, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyCdzaCYqLgUGhsSKUsG2bVHIgeLq7AFWOQ",
  authDomain: "oxfordform2.firebaseapp.com",
  projectId: "oxfordform2",
  storageBucket: "oxfordform2.appspot.com",
  messagingSenderId: "570492980550",
  appId: "1:570492980550:web:dfbc14f380cd7fcf4d6947",
  measurementId: "G-P3JLGDW0SN"
};

// Initialize Firebase and Firestore
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export { app };

// List of section keys to collect
const sectionKeys = [
  "form_section_a", "form_section_b", "form_section_c", "form_section_d", "form_section_e",
  "form_section_f", "form_section_g", "form_section_h", "form_section_i", "form_section_j",
  "form_section_k", "form_section_l",, "form_section_m"
]; // Add more if needed

// Submit button event listener
document.getElementById("submitBtn").addEventListener("click", async (e) => {
  e.preventDefault();

  try {
    const combinedData = {};

    // Collect and merge all section data
    
    // sectionKeys.forEach((key) => {
    //   const sectionData = JSON.parse(localStorage.getItem(key)) || {};
    //   Object.assign(combinedData, sectionData);
    // });

    sectionKeys.forEach((key) => {
      const data = JSON.parse(localStorage.getItem(key));
      if (data && typeof data === "object") {
        for (const [field, value] of Object.entries(data)) {
          if (field && field.trim() !== "") {
            combinedData[field] = value;
          }
        }
      }
    });

    // Upload to Firestore
	const timestamp = serverTimestamp();
    const docref = await addDoc(collection(db, "OxfordForm"), combinedData);
	const user = JSON.parse(localStorage.getItem("user"));
	await addDoc(collection(db, "applications"), {
		applicant_id: user.uid,
		created_at: timestamp,
		form_content_id: docref.id,
		programme: combinedData["major"],
		review_status: "unreviewed",
		submitted_at: null,
		reviewer_id: null,
	});
    // Clear localStorage
    sectionKeys.forEach((key) => localStorage.removeItem(key));
    alert("Form submitted successfully!");

    window.location.href = "submitted_form.html"; // Optional redirect
  } catch (error) {
    console.error("Error submitting form:", error);
    alert("Submission failed. Please try again.");
  }
});
