<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 12.04.2016
 * Time: 21:17
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Contains all SQL-Errors (easy add/print errors)
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class SQLError
 *
 * Static-Object
 */
class SQLError {
	/**
	 * The error Messages can be stored here before printing them
	 * If there is no error this var will is "unset"
	 *
	 * @var string[] - Error Message
	 */
	private static array $error;

	/**
	 * Get the Error-Message
	 *
	 * @return string[] - Error-Message
	 */
	public static function getError(): array {
		return self::$error;
	}

	/**
	 * Add an Error-Message
	 *
	 * @param string $error - Error-Message
	 */
	public static function addError(string $error): void {
		self::$error[] = $error;
	}

	/**
	 * Checks if an error exists
	 *
	 * @return bool - is there an error
	 */
	public static function isError(): bool {
		if(! isset(self::$error)) {
			return false;
		}

		return true;
	}

	/**
	 * Prints all Error-Messages or return them as string
	 *
	 * @param bool $returnString - Return value as String (true) or print (false - default)
	 * @return null|string - null if string is printed or empty else error-string with all errors
	 */
	public static function printError(bool $returnString = false): ?string {
		$code = null;

		foreach(self::getError() as $errMsg) {
			$code .= $errMsg . '<br />' . PHP_EOL;
		}

		if($returnString) {
			return $code;
		}

		echo $code;

		return null;
	}

	/**
	 * SQLError constructor. Disabled
	 */
	private function __construct() {
		// VOID
	}
}
