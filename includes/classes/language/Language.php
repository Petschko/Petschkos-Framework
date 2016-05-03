<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 28.04.2016
 * Time: 00:22
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Detects and outputs Language-Strings
 */

// Include Language-Strings and base class
require_once('LanguageStrings.php');
require_once('LangBaseClass.php');

/**
 * Singleton: Class Language
 */
class Language {
	/**
	 * Contains the instance of the lang class
	 *
	 * @var null|LangBaseClass - Instance of the language or null if none is set
	 */
	private static $language = null;

	/**
	 * Contains the Directory of the Language Files
	 *
	 * @var null|string - Directory or null if none is set
	 */
	private static $languageDir = null;

	/**
	 * Contains the allowed Languages-Files/Classes
	 *
	 * @var array - Allowed Language-Files/Classes
	 */
	private static $availableLanguages = array();

	/**
	 * Contains the name of the Cookie which is holing the language value
	 *
	 * @var string - Name of the Cookie
	 */
	private static $langCookieName = 'lang';

	/**
	 * Contains the name of the GET-Parameter which is holding the language value
	 *
	 * @var string - Name of the GET-Value
	 */
	private static $langGetName = 'lang';

	/**
	 * Contains the default Language
	 *
	 * @var string - Default Language
	 */
	private static $defaultLang = 'Deutsch';

	/**
	 * Contains if cookies are enabled
	 *
	 * @var bool - Are cookies enabled
	 */
	private static $cookiesEnabled = false;

	/**
	 * Language constructor.
	 */
	private function __construct() {}
	private function __clone() {}

	/**
	 * Detect the user Language
	 */
	private static function detectLanguage() {
		// Check if get language is changed
		if(! isset($_GET[self::getLangGetName()]))
			$_GET[self::getLangGetName()] = null;

		$lang = $_GET[self::getLangGetName()];

		if(self::isCookiesEnabled()) {
			// Check if Cookie is set
			if($lang === null || ! in_array($lang, self::getAvailableLanguages())) {
				if(! isset($_COOKIE[self::getLangCookieName()]))
					$_COOKIE[self::getLangCookieName()] = null;

				$lang = $_COOKIE[self::getLangCookieName()];
			}
		}

		// Set default language if there is no cookie or get
		if($lang === null || ! in_array($lang, self::getAvailableLanguages()))
			$lang = self::getDefaultLang();

		// Check if language is in allowed array
		if(in_array($lang, self::getAvailableLanguages()))
			self::setLang($lang);
		else {
			// Show error if language doesn't exists
			trigger_error('Can\'t detect language ' . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . '!', E_USER_ERROR);
			exit;
		}
	}

	/**
	 * Get the language class instance and set it if not set
	 *
	 * @return LangBaseClass - Language class instance
	 */
	public static function get() {
		if(self::$language === null)
			self::detectLanguage();

		return self::$language;
	}

	/**
	 * Set the available Languages
	 *
	 * @param array $availableLanguages - Available Languages
	 */
	public static function setAvailableLanguages($availableLanguages) {
		self::$availableLanguages = $availableLanguages;
	}

	/**
	 * Get the available Languages
	 *
	 * @return array - Available Languages
	 */
	private static function getAvailableLanguages() {
		return self::$availableLanguages;
	}

	/**
	 * Set the Language instance by Name - exit on error
	 *
	 * @param string $language - Language-Class and File-Name
	 */
	private static function setLang($language) {
		// Check if dir is set
		if(self::getLanguageDir() === null) {
			trigger_error('Language-dir is not set!', E_USER_ERROR);
			exit;
		}

		$language = mb_strtolower($language);
		if(function_exists('mb_ucfirst'))
			$language = mb_ucfirst($language);
		else
			$language = ucfirst($language);

		// Check if File exists
		if(file_exists(self::getLanguageDir() . basename($language) . '.php'))
			require_once(self::getLanguageDir() . basename($language) . '.php');
		else {
			trigger_error('File ' . htmlspecialchars(self::getLanguageDir()) . basename($language) . '.php doesn\'t exists...');
			exit;
		}

		// Set class
		if(class_exists($language))
			self::$language = new $language();
		else {
			// Return error on fail
			trigger_error('Error loading class ' . htmlspecialchars($language, ENT_QUOTES, 'UTF-8') . '!', E_USER_ERROR);
			exit;
		}
	}

	/**
	 * Set the Language Cookie-Name
	 *
	 * @param string $langCookieName - Language Cookie-Name
	 */
	public static function setLangCookieName($langCookieName) {
		self::$langCookieName = $langCookieName;
	}

	/**
	 * Get the Language Cookie-Name
	 *
	 * @return string - Language Cookie-Name
	 */
	public static function getLangCookieName() {
		return self::$langCookieName;
	}

	/**
	 * Get the default Language
	 *
	 * @return string - Default Language
	 */
	public static function getDefaultLang() {
		return self::$defaultLang;
	}

	/**
	 * Set the default Language
	 *
	 * @param string $defaultLang - Default Language
	 */
	public static function setDefaultLang($defaultLang) {
		self::$defaultLang = $defaultLang;
	}

	/**
	 * Get the Language GET-Name
	 *
	 * @return string - Language GET-Name
	 */
	public static function getLangGetName() {
		return self::$langGetName;
	}

	/**
	 * Set the Language GET-Name
	 *
	 * @param string $langGetName - Language GET-Name
	 */
	public static function setLangGetName($langGetName) {
		self::$langGetName = $langGetName;
	}

	/**
	 * Get the Language Directory
	 *
	 * @return null|string - Language Directory or null if none is set
	 */
	public static function getLanguageDir() {
		return self::$languageDir;
	}

	/**
	 * Set the Language Directory
	 *
	 * @param string $languageDir - Language Directory
	 */
	public static function setLanguageDir($languageDir) {
		// Check if dir ends with DS (/)
		if(mb_substr($languageDir, -1) != DIRECTORY_SEPARATOR)
			$languageDir = $languageDir . DIRECTORY_SEPARATOR;

		// Set Value
		self::$languageDir = $languageDir;
	}

	/**
	 * Shows if Cookie-check is enabled
	 *
	 * @return boolean - Is Cookie-Check enabled
	 */
	public static function isCookiesEnabled() {
		return self::$cookiesEnabled;
	}

	/**
	 * Set if Cookie-check is enabled
	 *
	 * @param boolean $cookiesEnabled - Is Cookie-Check enabled
	 */
	public static function setCookiesEnabled($cookiesEnabled) {
		self::$cookiesEnabled = $cookiesEnabled;
	}
}
