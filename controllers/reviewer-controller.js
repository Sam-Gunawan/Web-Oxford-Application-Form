import { db } from '../firebase.js';
import {
  collection,
  getDocs,
  getDoc,
  updateDoc,
  doc,
  query,
  where,
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

// GET applications where reviewer is included
export const getApplicationsByReviewer = async (reviewerId) => {
  const q = query(collection(db, 'applications'), where('reviewer_ids', 'array-contains', reviewerId));
  const snapshot = await getDocs(q);

  const applications = [];

  for (const docSnap of snapshot.docs) {
    const appData = docSnap.data();

    // Get applicant name
    let applicantName = "Unknown";
    if (appData.applicant_id) {
      try {
        const applicantDoc = await getDoc(doc(db, 'users', appData.applicant_id));
        if (applicantDoc.exists()) {
          applicantName = applicantDoc.data().name || "No Name";
        }
      } catch (error) {
        console.error("Error fetching applicant:", error);
      }
    }

    // Get reviewer names
    let reviewerNames = [];
    if (Array.isArray(appData.reviewer_ids)) {
      const reviewerPromises = appData.reviewer_ids.map(async (rid) => {
        try {
          const reviewerDoc = await getDoc(doc(db, 'users', rid));
          return reviewerDoc.exists() ? reviewerDoc.data().name || "No Name" : "Unknown";
        } catch (error) {
          console.error("Error fetching reviewer:", error);
          return "Unknown";
        }
      });

      reviewerNames = await Promise.all(reviewerPromises);
    }

    applications.push({
      id: docSnap.id,
      ...appData,
      applicant_name: applicantName,
      reviewer_names: reviewerNames,
    });
  }

  return applications;
};

export const approveApplication = async (applicationId) => {
  const appRef = doc(db, 'applications', applicationId);
  await updateDoc(appRef, { status: 'approved' });

  return { message: 'Application approved.' };
};

export const rejectApplication = async (applicationId) => {
  const appRef = doc(db, 'applications', applicationId);
  await updateDoc(appRef, { status: 'rejected' });

  return { message: 'Application rejected.' };
};
