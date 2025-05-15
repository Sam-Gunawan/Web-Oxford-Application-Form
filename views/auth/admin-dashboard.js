import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import {
  getFirestore,
  collection,
  getDocs,
  doc,
  updateDoc,
  deleteDoc
} from "https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore.js";

// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyCdzaCYqLgUGhsSKUsG2bVHIgeLq7AFWOQ",
  authDomain: "oxfordform2.firebaseapp.com",
  projectId: "oxfordform2",
  storageBucket: "oxfordform2.appspot.com",
  messagingSenderId: "570492980550",
  appId: "1:570492980550:web:dfbc14f380cd7fcf4d6947"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

// Reference to the table body
const userTableBody = document.getElementById("userTableBody");

// Load users on page load
window.onload = async () => {
  await loadUsers();
};

// Load users from Firestore
async function loadUsers() {
  userTableBody.innerHTML = "";
  const usersSnapshot = await getDocs(collection(db, "users"));
  usersSnapshot.forEach(docSnap => {
    const user = docSnap.data();
    const uid = docSnap.id;

    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${user.name || "-"}</td>
      <td>${user.email || "-"}</td>
      <td>
        <select class="form-select role-select" data-uid="${uid}">
          <option value="student" ${user.role === "student" ? "selected" : ""}>Student</option>
          <option value="reviewer" ${user.role === "reviewer" ? "selected" : ""}>Reviewer</option>
          <option value="admin" ${user.role === "admin" ? "selected" : ""}>Admin</option>
        </select>
      </td>
      <td>
        <button class="btn btn-danger btn-sm delete-btn" data-uid="${uid}">Delete</button>
      </td>
    `;
    userTableBody.appendChild(row);
  });

  // Add listeners for role changes
  document.querySelectorAll(".role-select").forEach(select => {
    select.addEventListener("change", async (e) => {
      const uid = e.target.getAttribute("data-uid");
      const newRole = e.target.value;
      await updateDoc(doc(db, "users", uid), { role: newRole });
      alert(`Role updated to ${newRole}`);
    });
  });

  // Add listeners for delete buttons
  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const uid = e.target.getAttribute("data-uid");
      if (confirm("Are you sure you want to delete this user?")) {
        await deleteDoc(doc(db, "users", uid));
        alert("User deleted.");
        await loadUsers(); // Reload table
      }
    });
  });
}

const fetchApplications = async () => {
  const querySnapshot = await getDocs(collection(db, "applications"));
  const applicationsTableBody = document.getElementById("applicationsTableBody");

  querySnapshot.forEach((doc, index) => {
    const applicationData = doc.data();
    
    // Create table row for each application
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${index + 1}</td>
      <td>${applicationData.studentName}</td>
      <td>${applicationData.email}</td>
      <td>${applicationData.role}</td>
      <td>
        <button class="btn btn-warning" onclick="editApplication('${doc.id}')">Edit</button>
        <button class="btn btn-danger" onclick="deleteApplication('${doc.id}')">Delete</button>
      </td>
    `;
    applicationsTableBody.appendChild(row);
  });
};

// Function to delete an application
const deleteApplication = async (id) => {
  if (confirm("Are you sure you want to delete this application?")) {
    await deleteDoc(doc(db, "applications", id));
    alert("Application deleted successfully!");
    // Refresh the list after deletion
    document.getElementById("applicationsTableBody").innerHTML = '';
    fetchApplications();
  };
}

// Function to edit an application (this could open a modal or redirect to another page)
const editApplication = (id) => {
  const updatedRole = prompt("Enter new role for the student (admin/reviewer/student):");
  
  if (updatedRole) {
    updateDoc(doc(db, "applications", id), {
      role: updatedRole
    }).then(() => {
      alert("Role updated successfully!");
      // Refresh the list after editing
      document.getElementById("applicationsTableBody").innerHTML = '';
      fetchApplications();
    }).catch((error) => {
      alert("Error updating role: " + error.message);
    });
  }
};

// Initial call to fetch applications
fetchApplications();
