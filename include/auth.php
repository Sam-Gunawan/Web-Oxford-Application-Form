<?php 
	if (!defined("SERVER")) {
		http_response_code(403);
		die();
	}

	require_once(__DIR__ . "/config.php");
	require_once(__DIR__ . "/../vendor/autoload.php");
	require_once(__DIR__ . "/router.php");
	session_start();

	use Firebase\Auth\Token\Exception\ExpiredToken;
	use Firebase\Auth\Token\Exception\InvalidSignature;
	use Firebase\Auth\Token\Exception\InvalidToken;
	use Firebase\Auth\Token\Exception\IssuedInTheFuture;
	use Firebase\Auth\Token\Exception\UnknownKey;
	use Kreait\Firebase\Auth\SignIn\FailedToSignIn;
	use Kreait\Firebase\Exception\Auth\RevokedIdToken;
	use Kreait\Firebase\Exception\AuthException;
	use Kreait\Firebase\Exception\FirebaseException;
	use Kreait\Firebase\Exception\InvalidArgumentException;
	use Kreait\Firebase\Factory as FirebaseFactory;
	use Symfony\Component\Cache\Adapter\FilesystemAdapter;
	use Symfony\Component\Cache\Psr16Cache;
	use Monolog\Handler\StreamHandler as StreamHandler;
	use Monolog\Logger;
	use \Logger as LocalLogger;

	$env = parse_ini_file(__DIR__ . "/../secret.env");
	$rdb_uri = $env["REALTIME_DATABASE_URI"];
	$service_acc = $env["SERVICE_ACCOUNT"];
	LocalLogger::debug($rdb_uri);
	LocalLogger::debug($service_acc);

	$auth_cache_pool = new FilesystemAdapter(
		"firebase_auth_cache", // Namespace for cache keys
		3600,                     // Default lifetime (1 h)
		sys_get_temp_dir() . "/firebase_auth_cache_files"
	);
	$verifier_cache = new FilesystemAdapter(
		"firebase_id_token_cache",
		3600,
		sys_get_temp_dir() . "/firebase_id_token_cache_files"
	);
	$logger = new Logger("http_firebase_logs");
	$logger->pushHandler(new StreamHandler(WEBSITE_ROOT . "/logs", Monolog\Level::Emergency));
	$factory = (new FirebaseFactory())
		->withServiceAccount($service_acc)
		->withDatabaseUri($rdb_uri)
		->withAuthTokenCache($auth_cache_pool)
		->withVerifierCache($verifier_cache)
		->withHttpLogger($logger);
	
	$auth = $factory->createAuth();
	$realtime_database = $factory->createDatabase();
	$cloud_messaging = $factory->createMessaging();
	$remote_config = $factory->createRemoteConfig();
	$cloud_storage = $factory->createStorage();
	$firestore = $factory->createFirestore();

	function get_filtered_email_password() {
		$filters = array(
			"user-email" => array (
				"filter" => FILTER_VALIDATE_EMAIL,
				"flags" => FILTER_NULL_ON_FAILURE
			),
			"user-password" => array (
				"filter" => FILTER_UNSAFE_RAW,
				"flags" => FILTER_NULL_ON_FAILURE
				)
		);
		$filtered_body = filter_input_array(INPUT_POST, $filters, false);
		$email = $filtered_body["user-email"];
		$password = $filtered_body["user-password"];

		LocalLogger::debug("E-mail: " . $email);
		LocalLogger::debug("Password: " . $password);

		return ["email" => $email, "password" => $password];
	}
	function verify_id() {
		global $auth;

		$verification_result = $auth->verifyIdToken($_SESSION["id_token"]);
		if (time() - $verification_result->claims()->get("auth_time") > 300) {
			return false;
		}
		$uid = $verification_result->claims()->get("sub");
		$_SESSION["role"] = $verification_result->claims()->get("role");
		
		$cookie = $auth->createSessionCookie($_SESSION["id_token"], new DateInterval("P1D"));
		return setcookie("user_session", $cookie, [
			"expires" => time() * 3600 * 24,
			"domain" => "oxfordweb.local",
			"secure" => true,
			"httponly" => true,
			"samesite" => "Lax"
		]);
	}

	function handle_signup_request(): bool {
		LocalLogger::debug("Handling signup.");
		global $auth;
		$filter_result = get_filtered_email_password();
		$id_verified = false;
		try {
			$email = $filter_result["email"];	
			$password = $filter_result["password"];
			$user = $auth->createUser([
				"email" => $email, 
				"password" => $password,
				"emailVerified" => false,
				"displayName" => substr($email, 0, strrpos($email, '@'))
			]);
			$auth->setCustomUserClaims($user->uid, ["role" => "student"]);
			$signed_in = $auth->signInWithEmailAndPassword($email, $password);
			$_SESSION["id_token"] = $signed_in->idToken();
			$id_verified = verify_id();
		} catch (AuthException $ae) {
			echo "AuthException: " . $ae->getMessage();
			LocalLogger::error("AuthException: " . $ae->getMessage());
			return false;
		} catch (FailedToSignIn $fsi) {
			echo "FailedToSignIn: " . $fsi->getMessage();
			LocalLogger::error("FailedToSignIn: " . $fsi->getMessage());
			return false;
		} catch (FirebaseException $fe) {
			echo "FirebaseException: " . $fe->getMessage();
			LocalLogger::error("FirebaseException: " . $fe->getMessage());
			return false;
		}
		return $id_verified;
	}

	function handle_login_request(): bool {
		LocalLogger::debug("Handling login.");	
		global $auth;
		
		$filter_result = get_filtered_email_password();
		$email = $filter_result["email"];	
		$password = $filter_result["password"];
		$id_verified = false;
		try {
			$signIn = $auth->signInWithEmailAndPassword($email, $password);
			$_SESSION["id_token"] = $signIn->idToken();
			$id_verified = verify_id();
		} catch (FailedToSignIn $fsi) {
			echo "FailedToSignIn: " . $fsi->getMessage();
			LocalLogger::error("FailedToSignIn: " . $fsi->getMessage());
			return false;
		}

		return $id_verified;
	}

	function debug_reset_session() {	
		// Unset all session variables
		$_SESSION = array(); // Clear the $_SESSION array
	
		// Destroy the session data on the server
		if (session_status() === PHP_SESSION_ACTIVE) { // Check if session is active before destroying
			session_destroy();
		}
	
		// Delete the session cookie (makes the browser forget the old session ID)
		// This is to trick the browser to thinking the cookie is expired
		$params = session_get_cookie_params();
		setcookie(
			session_name(), // The name of the session cookie (e.g., PHPSESSID)
			"", // Empty value
			time() - 42000, // A time in the past to expire the cookie
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);
	
		// Start a brand new session immediately after destroying the old one
		// This will generate a new session ID and cookie
		session_start(); // Start the new session
	
		LocalLogger::debug("New session started.");
	}

	function handle_form_submit() {
		$filters = [
			
		];
		$fields = filter_input_array(INPUT_POST, $filters);

		return $fields;
	}
	
	function redirect_on_unauthorized(?string $role = null) {
		global $auth;
		try {
			if (!CLEAN_URI && !isset($_SESSION["role"]) && ($_SESSION["role"] != $role || !$role)) {
				LocalLogger::warn("Unauthorized access of " . __FILE__); 
				redirect(WEBSITE_ROOT . LOGIN_PAGE_URL);
			}
		} catch (InvalidArgumentException 
		| InvalidToken 
		| InvalidSignature 
		| ExpiredToken 
		| IssuedInTheFuture 
		| UnknownKey 
		| RevokedIdToken $e) {
			if (session_status() === PHP_SESSION_ACTIVE) {
				session_destroy();
			}
			LocalLogger::error(get_class($e) . ": " . $e->getMessage());
			LocalLogger::warn("Unauthorized access of " . __FILE__); 
			redirect(WEBSITE_ROOT . LOGIN_PAGE_URL);
		}
	}
?>