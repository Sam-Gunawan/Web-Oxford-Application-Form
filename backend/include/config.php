<?php 
	declare(strict_types=1);

	define("SERVER_ROOT_URL", "/server");
	define("PAGES_ROOT_URL", "/views");
	define("FORM_PAGE_URL", PAGES_ROOT_URL . "/forms");
	define("DASHBOARD_URL", PAGES_ROOT_URL . "/dashboard");
	define("DASHBOARD_PAGE_URL", DASHBOARD_URL . "/dashboard.php");
	define("SERVER_AUTH_URL", SERVER_ROOT_URL . "/auth");
	define("LAYOUTS_URL", PAGES_ROOT_URL . "/layouts");
	define("AUTH_PAGES_URL", LAYOUTS_URL . "/auth");
	define("LOGIN_PAGE_URL", AUTH_PAGES_URL . "/login.php");
	define("SIGNUP_PAGE_URL", AUTH_PAGES_URL . "/signup.php");
	
	class Logger {
		private static ?string $default_log_dir = "C:/xampp/apache/logs/";
	
		// Optional: Method to set a global default log dir
		public static function set_default_log_dir(?string $dir_path): void {
			self::$default_log_dir = $dir_path;
		}
	
		// Core logging logic (based on your server_log function)
		// Made private as you'll call the public level methods (info, error, etc.)
		private static function __log(string $level, string $msg, ?string $file = null): bool {
			$timestamp = date('Y-m-d H:i:s');
			$fmt_msg = "[{$timestamp}] [{$level}] {$msg}\n";
	
			// Use the provided file path, or the default file path if set
			$target_file = $file ?? self::$default_log_dir;
	
			if ($target_file) {
				 // Ensure the log directory exists
				$log_directory = dirname($target_file);
				if (!is_dir($log_directory)) {
					mkdir($log_directory, 0775, true);
				}
				// Log to the specified file
				return error_log($fmt_msg, 3, $target_file . "oxford-web-" . strtolower($level) . ".log");
			} else {
				// If no file is specified and no default is set, log to PHP's default error destination
				return error_log($fmt_msg);
			}
		}
	
		public static function info(string $message, ?string $file = null): bool {
			return self::__log("INFO", $message, $file);
		}
	
		public static function error(string $message, ?string $file = null): bool {
			return self::__log("ERROR", $message, $file);
		}
	
		public static function warn(string $message, ?string $file = null): bool {
			return self::__log("WARNING", $message, $file);
		}
	
		public static function debug(string $message, ?string $file = null): bool {
			return self::__log("DEBUG", $message, $file);
		}
	}

?>