<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 28.04.2016
 * Time: 00:22
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Detects and outputs Language-Strings
 */

defined('BASE_DIR') or die('Invalid File-Access');

// Include Base-Class
require_once(LANG_DIR . DS . 'LangBase.php');

/**
 * Singleton: Class Language
 */
class Language {
	/**
	 * Contains the File-Name of the current Language
	 *
	 * @var null|string $currentLanguageFileName - Current Language File-Name
	 */
	private static $currentLanguageFileName = null;

	/**
	 * Contains the instance of the current lang class
	 *
	 * @var null|LangBase $language - Instance of the language or null if none is set
	 */
	private static $language = null;

	/**
	 * Contains the Directory of the Language PHP Class-Files
	 *
	 * @var null|string $languagePhpDir - Directory or null if none is set
	 */
	private static $languagePhpDir = null;

	/**
	 * Contains the Directory of the Language PHP Class-Files
	 *
	 * @var null|string $languageJsDir - Directory or null if none is set
	 */
	private static $languageJsDir = null;

	/**
	 * Contains the allowed Languages-Files/Classes
	 *
	 * @var array $availableLanguages - Allowed Language-Files/Classes
	 */
	private static $availableLanguages = array();

	/**
	 * Contains the name of the Cookie which is holing the language value
	 *
	 * @var string $langCookieName - Name of the Cookie
	 */
	private static $langCookieName = 'lang';

	/**
	 * Contains the name of the GET-Parameter which is holding the language value
	 *
	 * @var string $langGetName - Name of the GET-Value
	 */
	private static $langGetName = 'lang';

	/**
	 * Contains the default Language
	 *
	 * @var string $defaultLang - Default Language
	 */
	private static $defaultLang = 'lang.en';

	/**
	 * Contains if cookies are enabled
	 *
	 * @var bool $cookiesEnabled - Are cookies enabled
	 */
	private static $cookiesEnabled = false;

	/**
	 * Contains how long the cookie can life without refresh
	 *
	 * @var int $cookieExpTime - Cookie Lifetime from the last refresh in sec
	 */
	private static $cookieExpTime = 31104000;

	/**
	 * Disabled Language constructor.
	 */
	private function __construct() { /* VOID */ }

	/**
	 * Disabled Language clone function
	 */
	private function __clone() { /* VOID */ }

	/**
	 * Initiates the class and set all important values
	 */
	private static function init() {
		// Detect the current language from the user
		$detected = false;
		// Check if lang changed via GET
		if(isset($_GET[self::getLangGetName()])) {
			if(self::validateLangValue($_GET[self::getLangGetName()])) {
				self::setCurrentLanguageFileName($_GET[self::getLangGetName()]);
				self::setLangCookie();
				$detected = true;
			}
		}
		// Check if language exists in Cookie if not changed via GET
		if(! $detected) {
			if(self::validateLangValue(self::getLangCookieValue())) {
				self::setCurrentLanguageFileName(self::getLangCookieValue());
				self::setLangCookie(); // Refresh it^^
				$detected = true;
			}
		}
		// Use default if not in cookie or get
		if(! $detected) {
			self::setCurrentLanguageFileName(self::getDefaultLang());
			self::setLangCookie();
		}

		// Setup rest values
		self::requireLangClassFileOnce();
		self::setLanguage(new self::$availableLanguages[self::getCurrentLanguageFileName()]());
	}

	/**
	 * Get the language class instance and set it if not set
	 *
	 * @return LangBase - Language class instance
	 */
	public static function out() {
		if(self::getLanguage() === null)
			self::init();

		return self::getLanguage();
	}

	/**
	 * Outputs the current JS-Language File URI
	 *
	 * @return string - JS-Language File URI
	 */
	public static function getLangJsFileUri() {
		return self::getLanguageJsDir() . basename(self::getCurrentLanguageFileName()) . '.js';
	}

	/**
	 * @return null|string
	 */
	private static function getCurrentLanguageFileName() {
		return self::$currentLanguageFileName;
	}

	/**
	 * @param null|string $currentLanguageFileName
	 */
	private static function setCurrentLanguageFileName($currentLanguageFileName) {
		self::$currentLanguageFileName = $currentLanguageFileName;
	}

	/**
	 * @return LangBase|null
	 */
	private static function getLanguage() {
		return self::$language;
	}

	/**
	 * @param LangBase|null $language
	 */
	private static function setLanguage($language) {
		self::$language = $language;
	}

	/**
	 * @return null|string
	 */
	private static function getLanguagePhpDir() {
		return self::$languagePhpDir;
	}

	/**
	 * @param null|string $languagePhpDir
	 */
	public static function setLanguagePhpDir($languagePhpDir) {
		self::$languagePhpDir = $languagePhpDir;
	}

	/**
	 * @return null|string
	 */
	private static function getLanguageJsDir() {
		return self::$languageJsDir;
	}

	/**
	 * @param null|string $languageJsDir
	 */
	public static function setLanguageJsDir($languageJsDir) {
		self::$languageJsDir = $languageJsDir;
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
	private static function getLangCookieName() {
		return self::$langCookieName;
	}

	/**
	 * Get the default Language
	 *
	 * @return string - Default Language
	 */
	private static function getDefaultLang() {
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
	private static function getLangGetName() {
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
	 * Shows if Cookie-check is enabled
	 *
	 * @return boolean - Is Cookie-Check enabled
	 */
	private static function isCookiesEnabled() {
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

	/**
	 * @return int
	 */
	private static function getCookieExpTime() {
		return self::$cookieExpTime;
	}

	/**
	 * @param int $cookieExpTime
	 */
	public static function setCookieExpTime($cookieExpTime) {
		self::$cookieExpTime = $cookieExpTime;
	}

	/**
	 * Set/Refresh the Language Cookie
	 */
	private static function setLangCookie() {
		if(! self::isCookiesEnabled())
			return;

		setcookie(self::getLangCookieName(), self::getCurrentLanguageFileName(), time() + self::getCookieExpTime());
	}

	/**
	 * Returns the Value of the User-Language Cookie or null if not set/allowed
	 *
	 * @return null|string - Language Cookie-Value
	 */
	private static function getLangCookieValue() {
		if(! self::isCookiesEnabled())
			return null;

		return (isset($_COOKIE[self::getLangCookieName()])) ? $_COOKIE[self::getLangCookieName()] : null;
	}

	/**
	 * Checks if the Language exists
	 *
	 * @param mixed $value - Value to check if exists
	 * @return string - true if language is valid else false
	 */
	private static function validateLangValue($value) {
		return array_key_exists($value, self::getAvailableLanguages());
	}

	/**
	 * Includes the current Language PHP File
	 *
	 * @throws Exception - File not Found exception
	 */
	private static function requireLangClassFileOnce() {
		$file = self::getLanguagePhpDir() . DS . basename(self::getCurrentLanguageFileName()) . '.php';

		if(file_exists($file))
			require_once($file);
		else
			throw new Exception(__CLASS__ . '-File "' . $file . '" doesn\'t exists... Please fix this error by creating that file!');
	}
}
