<?php
	session_start();
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$json = json_decode(file_get_contents("php://input"), true);
		$_SESSION["user"] = $json;
		echo json_encode(["success" => true]);
		exit();
	}
	$user = null;
	if ($_SERVER["REQUEST_METHOD"] === "GET") {
		$user = $_SESSION["user"];
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo htmlspecialchars($user["username"]);?>'s Application Form</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous"  media="all">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
		<link rel="stylesheet" href="../../../assets/css/adminView.css">
	</head>
	<body>
		<div class="parent-container bg-white">
			<!-- nav section -->
			<div class="d-flex flex-column grid-nav bg-primary text-white d-flex align-items-center justify-content-center"> <!-- the nav container -->
				<div class="bg-white p-3 d-flex rounded align-items-center justify-content-center" style="max-height: 33.3%; width: 100%;">
					<img src="../../../assets/images/Oxford-University-Circlet.svg.png" alt="Oxford Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;">
				</div>
				<div class="mt-4 nav-content d-flex flex-column flex-grow-1 pt-4 gap-2 w-100">
					
				</div>
			</div>
			
			<!-- title section -->
			<div class="grid-title text-dark d-flex align-items-center justify-content-between">
			<div class="title-text display-3 color-primary"><span id="userName">Welcome </span></div>
			<div class="top-nav h-50 d-flex align-items-center gap-4">
				<i class="fa-regular fa-bell fa-3x color-primary"></i>
				<button id="logoutBtn" class="btn p-0 border-0 bg-transparent">
					<i class="fa-solid fa-right-from-bracket fa-3x color-primary"></i>
				</button>
			</div>
			</div>

			<div class="grid-content bg-secondary d-flex flex-column align-items-star gap-2 overflow-auto"> <!-- content container -->
				<div id="app-container" class="d-flex flex-column justify-content-around h-75">
					<div class="form-group row w-100">
						<label for="username" class="form-label col-sm-2">Name</label>
						<input id="username" name="username" class="form-control col" style="max-width: 600px; min-width: 300px;" type="text" readonly value="<?php echo htmlspecialchars($user["username"]); ?>">
					</div>
					<div class="form-group row w-100">
						<label for="date-of-birth" class="form-label col-sm-2">Date of Birth</label>
						<input id="date-of-birth" name="date-of-birth" class="form-control col" style="max-width: 600px; min-width: 300px;" type="text" readonly value="<?php echo htmlspecialchars($user["dob"]); ?>">
					</div>
					<div class="form-group row w-100">
						<label for="country-of-birth" class="form-label col-sm-2">Country of Birth</label>
						<input id="country-of-birth" name="country-of-birth" class="form-control col" style="max-width: 600px; min-width: 300px;" type="text" readonly value="<?php echo htmlspecialchars($user["countryOfBirth"]); ?>">
					</div>
					<div class="form-group row w-100">
						<label for="major" class="form-label col-sm-2">Programme</label>
						<input id="major" name="major" class="form-control col" style="max-width: 600px; min-width: 300px;" type="text" readonly value="<?php echo htmlspecialchars($user["major"]); ?>">
					</div>
					<div class="form-group row w-100">
						<label for="speaking-level" class="form-label col-sm-2">English Speaking Level</label>
						<input id="speaking-level" name="speaking-level" class="form-control col" style="max-width: 600px; min-width: 300px;" type="text" readonly value="<?php echo htmlspecialchars($user["speakingLevel"]); ?>">
					</div>
					<div class="form-group row w-100">
						<label for="email" class="form-label col-sm-2">E-mail</label>
						<input id="email" name="email" class="form-control col" style="max-width: 600px; min-width: 300px;" type="text" readonly value="<?php echo htmlspecialchars($user["emailAddress"]); ?>">
					</div>
				</div>
				<button class="btn btn-primary" onclick="history.back()">Go Back</button>
			</div>
		</div>
		<script>
			const user = JSON.parse(localStorage.getItem("user"));
			if (!user) {
				window.location.href = "../../../index.html";
			}
			document.getElementById("userName").textContent += user.name;
			document.getElementById("logoutBtn").addEventListener("click", () => {
				localStorage.removeItem("user");
				window.location.href = "../../../index.html"; // change to your actual login path
			});
		</script>
	</body>
</html>