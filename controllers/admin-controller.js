import { db } from '../firebase.js';
import {
  collection,
  getDocs,
  getDoc,
  setDoc,
  deleteDoc,
  doc,
  updateDoc,
  query,
  where,
  Timestamp
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

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
        applicantName = applicantData.name;
      }
    } catch (e) {
      console.error(`Failed to fetch applicant: ${appData.applicant_id}`, e);
    }

    // Fetch reviewer name
	let reviewerName = "";
	try {
		console.log(appData.reviewer_id);
		const reviewerSnap = await getDoc(doc(db, "users", appData.reviewer_id));
		if (reviewerSnap.exists()) {
			const reviewerData = reviewerSnap.data();
			reviewerName = reviewerData.name;
		}
		console.log("reviewername: ", reviewerName);
	} catch (e) {
		console.error(`Failed to fetch reviewer: ${appData.reviewer_id}`, e);
	}

    applications.push({
      id: appDoc.id,
      ...appData,
      applicant_name: applicantName,
      reviewer_name: reviewerName
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

export async function assignReviewersToApp(appId, reviewerId) {
    const appRef = doc(db, 'applications', appId);
    await updateDoc(appRef, {
        reviewer_id: reviewerId
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
  await deleteDoc(doc(db, "applications", applicationId));
}

// // --- Edit an Application ---
export async function editApplication(applicationId, data) {
  await updateDoc(doc(db, "applications", applicationId), data);
}

// --- Delete a User ---
export async function deleteUser(userId) {
  await deleteDoc(doc(db, "users", userId));
}

// --- Edit a User ---
export async function editUser(userId, data) {
  await updateDoc(doc(db, "users", userId), data);
}