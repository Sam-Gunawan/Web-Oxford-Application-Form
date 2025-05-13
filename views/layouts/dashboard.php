<?php

session_start();
$user = $_SESSION["user"];
$username = htmlspecialchars($user['displayName'] !== null ? $user["displayName"] : substr($user["email"], 0, strrpos($user["email"], '@')));

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Grid Layout Page</title>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

		<link rel="stylesheet" href="../../assets/css/adminView.css">
	</head>
	<body>
		<script type="module">
			import { auth, db } from "../../firebase.js";
			import { collection, getDocs, getDoc, setDoc, deleteDoc, doc, updateDoc, query, where, Timestamp, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js"; 
			async function message(email, msg) {
				try {
					const response = await fetch("../../api/mail.php", {
						method: "POST",
						headers: {
							"Content-Type": "application/json"
						},
						body: JSON.stringify({
							email:email, 
							msg:msg
						})
					})
					const result_text = await response.json();
					return true;
				} catch (err) {
					console.error(err);
					return false;
				}
			}
			async function approve(event) {
				const appId = event.target.dataset.applicationId;
				const email = event.target.dataset.email;
				const msg = "approved";
				try {
					await updateDoc(doc(db, "student_applications", appId), {
						date_reviewed: serverTimestamp(),
						review_status: "approved",
						review_message: msg
					});
					if (message(email, msg)) {
						event.target.parentElement.parentElement.getElementsByClassName("review-status")[0].innerHTML = "<p style=\"color: green;\">approved</p>";
					}
				} catch (e) {
					console.error(e);
				}
			}
			async function reject(event) {
				const appId = event.target.dataset.applicationId;
				const email = event.target.dataset.email;
				const msg = "rejected";
				try {
					await updateDoc(doc(db, "student_applications", appId), {
						date_reviewed: serverTimestamp(),
						review_status: "rejected",
						review_message: email
					});
					if (message(email, msg)) {
						event.target.parentElement.parentElement.getElementsByClassName("review-status")[0].innerHTML = "<p style=\"color: red;\">rejected</p>";
					}
				} catch (e) {
					console.error(e);
				}
			}
			document.addEventListener("DOMContentLoaded", async () => {
				const user = <?php echo json_encode($_SESSION["user"]);?>;
				const role = user["role"];
				function escapeHTML(str) {
					if (typeof str === null || str === undefined) return ''; // Handle null/undefined gracefully
					if (typeof str !== 'string') str = String(str); // Ensure it's a string
					const div = document.createElement('div');
					div.textContent = str;
					return div.innerHTML;
				}

				if (role === "reviewer") {
					const contentDiv = document.getElementById("content");
					async function fetchApplications() {
						contentDiv.innerHTML = `<p>Loading...</p>`; // Clear previous content/placeholder
						try {
							// --- Fetch Data from Firestore (Client-Side JS SDK) ---
							const applicationsSnapshot = await getDocs(collection(db, "student_applications"));
							const applicationsData = applicationsSnapshot.docs.map(doc => ({
								id: doc.id,
								...doc.data()
							})); // Map to plain objects including the document ID

							if (applicationsData.length === 0) {
								contentDiv.innerHTML = "<p>No applications found.</p>";
							} else {
								let htmlContent = '';
								applicationsData.forEach(app => {
									const collapseId = `application${app.id}`; // Construct collapse ID using JS
									htmlContent += `
										<div class="card mb-2 shadow-sm rounded" data-application-id="${escapeHTML(app.id)}"> 
											<div class="content-item bg-white p-4 d-flex align-items-center justify-content-between w-100">
												<div class="d-flex align-items-center gap-3">
													<i class="fa-regular fa-circle-user fa-2x"></i>
													<span>${ escapeHTML(app.applicant_email || 'N/A') }</span> 
												</div>

												<div class="d-flex align-items-center gap-2">
													<button class="btn toggle-btn" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
														<i class="fa fa-caret-down"></i>
													</button>
												</div>
											</div>

											<div class="collapse bg-secondary" id="${collapseId}">
												<div class="card-body border-top d-flex justify-content-end px-5">
													
													<div class="content-info d-flex flex-column gap-2">
														<strong>Application Info</strong>
														<div>${ escapeHTML(app.submission_date.toDate() || 'N/A') }</div>
														<div>${ escapeHTML(app.programme || 'N/A') }</div>
														<div class="fw-bold review-status">${ escapeHTML(app.review_status || 'N/A') === "approved" ? `<p style="color: green;">${escapeHTML(app.review_status || 'N/A')}` : `<p style="color: red;">${escapeHTML(app.review_status || 'N/A')}` }</p></div>
													</div>
													<div class="content-info d-flex flex-column gap-2">
														<strong>Reviewer</strong>
														${ (app.reviewers && Array.isArray(app.reviewers) && app.reviewers.length > 0)
															// Map reviewers array to HTML divs and join
															? app.reviewers.map(reviewer => `<div>${escapeHTML(reviewer || 'N/A')}</div>`).join('')
															: '<div>N/A</div>' // Handle case with no reviewers
														}
													</div>
												</div>
												<div class="d-flex align-items-center gap-3 w-100 justify-content-end px-5 py-3 border-top border-white">
													<button class="btn bg-primary text-white approve-btn" data-application-id="${escapeHTML(app.id)}" data-email="${escapeHTML(app.applicant_email || "")}" style="min-width: 10%;">Approve</button>
													<button class="btn bg-primary text-white reject-btn" data-application-id="${escapeHTML(app.id)}" data-email="${escapeHTML(app.applicant_email || "")}" style="min-width: 10%;">Reject</button>
												</div>
											</div>
										</div>
									`;
								});
								// Insert all generated HTML into the container after the loop
								contentDiv.innerHTML = htmlContent;
								const approveButtons = contentDiv.querySelectorAll('.approve-btn');
								approveButtons.forEach(button => {
									button.addEventListener('click', approve); // Attach the approve function
								});

								const rejectButtons = contentDiv.querySelectorAll('.reject-btn');
								rejectButtons.forEach(button => {
									button.addEventListener('click', reject); // Attach the reject function
								});
							}
							console.info("success");
						} catch (e) {
							console.error("Error fetching applications:", e);
							contentDiv.innerHTML = `<p>Error loading applications. (${e})</p>`; // Show error in display area
						}
					}
					await fetchApplications();
				} else if (claims.role === "admin") {

				} else {

				}
			});
		</script>
		<div class="parent-container bg-white">
			<!-- nav section -->
			<div class="d-flex flex-column grid-nav bg-primary text-white d-flex align-items-center justify-content-center"> <!-- the nav container -->
				<div class="bg-white p-3 d-flex rounded align-items-center justify-content-center" style="max-height: 33.3%; width: 100%;">
				<img src="../../assets/images/Oxford-University-Circlet.svg.png" alt="Oxford Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;">
				</div>
				<div class="mt-4 nav-content d-flex flex-column flex-grow-1 pt-4 gap-2 w-100">
					<?php if ($user['role'] === 'admin'): ?>
						<a href="?page=applications" class="nav-item border-bottom p-2 text-decoration-none">Application List</a>
						<a href="?page=users" class="nav-item border-bottom p-2 text-decoration-none">User List</a>
						<a href="?page=statistics" class="nav-item border-bottom p-2 text-decoration-none">Statistic</a>
					<?php elseif ($user['role'] === 'reviewer'): ?>
						<a class="nav-item border-bottom p-2 text-decoration-none">Application List</a>
					<?php elseif ($user['role'] === 'student'): ?>
						<a  href="?page=applications" class="nav-item border-bottom p-2 text-decoration-none">Application List</a>
						<a  href="?page=applicationsDraft"class="nav-item border-bottom p-2 text-decoration-none">Application Draft</a>
					<?php endif; ?>
				</div>
			</div>

			<!-- title section -->
			<div class="grid-title text-dark d-flex align-items-center justify-content-between">
				<div class="title-text display-3 color-primary">Welcome <?php echo $username; ?></div>
				<div class="top-nav h-50 d-flex align-items-center gap-3">
					<i class="fa-regular fa-bell fa-3x color-primary"></i>
					<i class="fa-regular fa-circle-user fa-3x color-primary"></i>
				</div>
			</div>

			<!-- content section  -->
			<div id="content" class="grid-content bg-secondary d-flex flex-column align-items-star gap-2 overflow-auto"> <!-- content container -->
				<!-- <?php
					switch ($user['role']) {
						case 'admin':
							$page = $_GET['page'] ?? 'applications';
							switch ($page) {
								case 'applications':
									include('adminView/application-list.php');
									break;
								case 'users':
									include('adminView/user-list.php');
									break;
								case 'statistics':
									include('adminView/admin-statistic.php');
									break;
								default:
									echo "<div class='p-4'>Admin page not found.</div>";
							}
							break;

						case 'student':
							$page = $_GET['page'] ?? 'applications';
							switch ($page) {
								case 'applications':
									include('studentView/application-list.php');
									break;
								case 'applicationsDraft':
									include('studentView/application-draft.php');
									break;
								default:
									echo "<div class='p-4'>page not found.</div>";
							}
							// include('studentView/application-list.php');
							break;

						default:
							echo "Role not recognized.";
					}
				?> -->
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="../../assets/js/adminView.js"></script>
	</body>
</html>
	