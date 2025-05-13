import { db } from '../firebase.js';
import { collection, getDocs, getDoc, setDoc, deleteDoc, doc, updateDoc, query, where, Timestamp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

// --- Get all Applications ---
export async function getAllApplications() {
  const applicationsSnapshot = await getDocs(collection(db, "applications"));
  const applications = [];

  for (const appDoc of applicationsSnapshot.docs) {
    const appData = appDoc.data();

    // Fetch applicant name
    let applicantName = "Unknown Student";
    try {
      const applicantSnap = await getDoc(doc(db, "users", appData.applicant_id));
      if (applicantSnap.exists()) {
        const applicantData = applicantSnap.data();
        applicantName = applicantData.name || "Unnamed Student";
      }
    } catch (e) {
      console.error(`Failed to fetch applicant: ${appData.applicant_id}`, e);
    }

    // Fetch reviewer names
    const reviewerNames = [];
    for (const reviewerId of appData.reviewer_ids || []) {
      try {
        const reviewerSnap = await getDoc(doc(db, "users", reviewerId));
        if (reviewerSnap.exists()) {
          const reviewerData = reviewerSnap.data();
          reviewerNames.push(reviewerData.name || "Unnamed Reviewer");
        } else {
          reviewerNames.push("Unknown Reviewer");
        }
      } catch (e) {
        console.error(`Failed to fetch reviewer: ${reviewerId}`, e);
        reviewerNames.push("Error Reviewer");
      }
    }

    applications.push({
      id: appDoc.id,
      ...appData,
      applicant_name: applicantName,
      reviewer_names: reviewerNames
    });
  }

  return applications;
}

// --- Get all Reviewer ---
export async function getAllReviewers() {
    const reviewersRef = collection(db, "users");
    const q = query(reviewersRef, where("role", "==", "reviewer"));
    const querySnapshot = await getDocs(q);

    return querySnapshot.docs.map(doc => ({id: doc.id,...doc.data()}));
}

export async function assignReviewersToApp(appId, reviewerIds) {
    const appRef = doc(db, 'applications', appId);
    await updateDoc(appRef, {
        reviewer_ids: reviewerIds
    });
}

// --- Get all Users ---
export async function getAllUsers() {
  const usersRef = collection(db, "users");
  const snapshot = await getDocs(usersRef);
  return snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
}

// --- Delete an Application ---
export async function deleteApplication(applicationId) {
  await deleteDoc(doc(db, "student_applications", applicationId));
}

// // --- Edit an Application ---
export async function editApplication(applicationId, data) {
  await updateDoc(doc(db, "student_applications", applicationId), data);
}

// --- Delete a User ---
export async function deleteUser(userId) {
  await deleteDoc(doc(db, "users", userId));
}

// --- Edit a User ---
export async function editUser(userId, data) {
  await updateDoc(doc(db, "users", userId), data);
}

// async function insertDummyData() {
//   const now = Timestamp.now();

//   const users = [
//     // Students
//     { id: "student1", name: "Alice Johnson", email: "alice@student.com", password: "alice123", role: "student", created_at: now },
//     { id: "student2", name: "Bob Smith", email: "bob@student.com", password: "bob123", role: "student", created_at: now },
//     { id: "student3", name: "Cathy Lee", email: "cathy@student.com", password: "cathy123", role: "student", created_at: now },

//     // Reviewers
//     { id: "reviewer1", name: "Dr. Emily Stone", email: "emily@reviewer.com", password: "emily123", role: "reviewer", created_at: now },
//     { id: "reviewer2", name: "Prof. David Green", email: "david@reviewer.com", password: "david123", role: "reviewer", created_at: now },
//     { id: "reviewer3", name: "Dr. Sarah White", email: "sarah@reviewer.com", password: "sarah123", role: "reviewer", created_at: now },

//     // Admin
//     { id: "admin1", name: "Admin User", email: "admin@site.com", password: "admin123", role: "admin", created_at: now }
//   ];

//   const applications = [
//     {
//       id: "app1",
//       applicant_id: "student1",
//       reviewer_ids: ["reviewer1", "reviewer2"],
//       programme: "Computer Science",
//       status: "rejected",
//       content: "form1",
//       reviewer_message: null,
//       created_at: now,
//       submitted_at: now
//     },
//     {
//       id: "app2",
//       applicant_id: "student2",
//       reviewer_ids: ["reviewer2", "reviewer3"],
//       programme: "Mechanical Engineering",
//       status: "waitlist",
//       content: "form2",
//       reviewer_message: null,
//       created_at: now,
//       submitted_at: null
//     },
//     {
//       id: "app3",
//       applicant_id: "student3",
//       reviewer_ids: ["reviewer1", "reviewer3"],
//       programme: "Electrical Engineering",
//       status: "accepted",
//       content: "form3",
//       reviewer_message: "Excellent application.",
//       created_at: now,
//       submitted_at: now
//     }
//   ];

//   try {
//     for (const user of users) {
//       await setDoc(doc(db, "users", user.id), user);
//       console.log(`✅ User added: ${user.id}`);
//     }

//     for (const app of applications) {
//       await setDoc(doc(db, "applications", app.id), app);
//       console.log(`✅ Application added: ${app.id}`);
//     }

//     console.log("🎉 All dummy data inserted successfully.");
//   } catch (error) {
//     console.error("❌ Error inserting dummy data:", error);
//   }
// }

// insertDummyData();