<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 12.04.2016
 * Time: 09:17
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

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
	 * @var array - Error Message
	 */
	private static $error;

	/**
	 * Get the Error-Message
	 *
	 * @return array - Error-Message
	 */
	public static function getError() {
		return self::$error;
	}

	/**
	 * Add an Error-Message
	 *
	 * @param string $error - Error-Message
	 */
	public static function addError($error) {
		self::$error[] = $error;
	}

	/**
	 * Checks if an error exists
	 *
	 * @return bool - is there an error
	 */
	public static function isError() {
		if(! isset(self::$error))
			return false;

		return true;
	}

	/**
	 * Prints all Error-Messages
	 */
	public function printError() {
		foreach(self::getError() as $errMsg) {
			echo $errMsg . '<br />' . PHP_EOL;
		}
	}

	/**
	 * SQLError constructor. Disabled
	 */
	private function __construct() {}
}
