import { auth, db } from "../../firebase.js";
import { collection, getDocs, getDoc, setDoc, deleteDoc, doc, updateDoc, query, where, Timestamp, addDoc } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", function (event) {
	const form = document.getElementById("application-form");
	const uploadPdfModalElement = document.getElementById('upload-pdf-modal');
	const pdfUrlInput = document.getElementById('pdf-url-input');
	const uploadStatus = document.getElementById('upload-status');
	const submitPdfLinkButton = document.getElementById('submit-pdf-link');
	const cancelButton = document.getElementById("cancel-upload-button");
	const closeButton = document.getElementById("close-button");
	
	const uploadPdfModal = new bootstrap.Modal(uploadPdfModalElement);
	
	function resetUploadModal() {
		pdfUrlInput.value = ''; 
		submitPdfLinkButton.disabled = true;
		uploadStatus.textContent = '';
	}

	function showUploadModal() {
		// Reset input and status when showing
		resetUploadModal();
		uploadPdfModal.show(); 
	}

	async function handleSubmit(ev) {
		ev.preventDefault();
		window.print();
		showUploadModal();
	}

	pdfUrlInput.addEventListener('input', (event) => {
        const url = event.target.value.trim();
		submitPdfLinkButton.disabled = url === "";
		uploadStatus.textContent = "";
    });
	
	submitPdfLinkButton.addEventListener('click', async () => {
        const pdfUrl = pdfUrlInput.value.trim();
        if (pdfUrl === "") {
            uploadStatus.textContent = 'Please enter a PDF link.';
            return;
        }
		try {
			new URL(pdfUrl);
		} catch (_) {
			uploadStatus.textContent = 'Please enter a valid URL.';
			return;
		}

        submitPdfLinkButton.disabled = true;
        uploadStatus.textContent = 'Submitting link...'; 
        let data = new FormData(form);
		data.append("pdf-url", pdfUrl);
		try {
			const datetime = new Date().toUTCString();
			const uid = auth.currentUser.uid;
			const claims = await auth.currentUser.getIdTokenResult();
			if (claims.role === "reviewer" || claims.role === "admin") {
				throw new Error(`User with role ${claims.role} does not have permission to apply.\n`);
			}
			if (!uid) {
				throw new Error("Cannot get UID of current user.\n");
			}
			const docRef = await addDoc(collection(db, "student_applications"), {
				submission_date: datetime,
				applicant_uid: uid,
				review_status: "unreviewed",
				pdf_url: pdfUrl,
			});
			resetUploadModal();
			uploadPdfModal.hide();
		} catch (e) {
			console.error("Error adding document: ", e);
		}
    });

	function cancel(event) {
		resetUploadModal();
		uploadPdfModal.hide();
	}

	cancelButton.addEventListener("click", cancel);
	closeButton.addEventListener("click", cancel);
	form.addEventListener("submit", handleSubmit);
});