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
  const q = query(collection(db, 'applications'), where('reviewer_id', "==", reviewerId));
  const snapshot = await getDocs(q);

  const applications = [];

  for (const docSnap of snapshot.docs) {
    const appData = docSnap.data();
    // Get applicant name
    let applicantName = null;
    if (appData.applicant_id) {
      try {
        const applicantDoc = await getDoc(doc(db, 'users', appData.applicant_id));
		applicantName = applicantDoc.data().name;
      } catch (error) {
        console.error("Error fetching applicant:", error);
      }
    }

    // Get reviewer names
    let reviewerName = null;
    if (appData.reviewer_id) {
		try {
			const reviewerDoc = await getDoc(doc(db, 'users', appData.reviewer_id));
			reviewerName = reviewerDoc.data().name;
		} catch (error) {
			console.error("Error fetching reviewer:", error);
			reviewerName = "";
		}
    }

    applications.push({
		id: docSnap.id,
		...appData,
		applicant_name: applicantName,
		reviewer_name: reviewerName,
    });
  }

  return applications;
};

export const approveApplication = async (applicationId) => {
  const appRef = doc(db, 'applications', applicationId);
  try {
	const app = await getDoc(appRef).then((app) => {
		return app.data();
	})
	await updateDoc(appRef, { review_status: 'approved' });
	const response = await fetch("../../../api/mail.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify({
			email: app.email,
			msg: "approved"
		})
	});
	const result = await response.json();
	console.log(result.success ? "Sent approval email" : "Failed to send approval email");
  } catch (e) {
	return { message: 'Failed to approve. (' + e + ")" };
  }

  return { message: 'Application approved.' };
};

export const rejectApplication = async (applicationId) => {
  const appRef = doc(db, 'applications', applicationId);
  try {
	const app = await getDoc(appRef).then((app) => {
		return app.data();
	})
	await updateDoc(appRef, { review_status: 'rejected' });
	const response = await fetch("../../../api/mail.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify({
			email: app.email,
			msg: "rejected"
		})
	});
	const result = await response.json();
	console.log("result: ", result);
	console.log(result.success ? "Sent rejection email" : "Failed to send rejection email");
  } catch (e) {
	return { message: 'Failed to reject. (' + e + ")" };
  }
  return { message: 'Application rejected.' };
};
