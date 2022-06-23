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
	 * Contains if the init function has run
	 *
	 * @var bool $init - Init function ran
	 */
	private static bool $init = false;

	/**
	 * Contains the File-Name of the current Language
	 *
	 * @var null|string $currentLanguageFileName - Current Language File-Name
	 */
	private static ?string $currentLanguageFileName = null;

	/**
	 * Contains the instance of the current lang class
	 *
	 * @var null|LangBase $language - Instance of the language or null if none is set
	 */
	private static ?LangBase $language = null;

	/**
	 * Contains the Directory of the Language PHP Class-Files
	 *
	 * @var null|string $languagePhpDir - Directory or null if none is set
	 */
	private static ?string $languagePhpDir = null;

	/**
	 * Contains the Directory of the Language PHP Class-Files
	 *
	 * @var null|string $languageJsDir - Directory or null if none is set
	 */
	private static ?string $languageJsDir = null;

	/**
	 * Contains the allowed Languages-Files/Classes
	 *
	 * @var string[] $availableLanguages - Allowed Language-Files/Classes
	 */
	private static array $availableLanguages = [];

	/**
	 * Contains the name of the Cookie which is holing the language value
	 *
	 * @var string $langCookieName - Name of the Cookie
	 */
	private static string $langCookieName = 'lang';

	/**
	 * Contains the name of the GET-Parameter which is holding the language value
	 *
	 * @var string $langGetName - Name of the GET-Value
	 */
	private static string $langGetName = 'lang';

	/**
	 * Contains the default Language
	 *
	 * @var string $defaultLang - Default Language
	 */
	private static string $defaultLang = 'lang.en';

	/**
	 * Contains if cookies are enabled
	 *
	 * @var bool $cookiesEnabled - Are cookies enabled
	 */
	private static bool $cookiesEnabled = false;

	/**
	 * Contains how long the cookie can life without refresh
	 *
	 * @var int $cookieExpTime - Cookie Lifetime from the last refresh in sec
	 */
	private static int $cookieExpTime = 31104000;

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
	 *
	 * @throws Exception
	 */
	private static function init(): void {
		// Only run once
		if(self::$init) {
			return;
		}

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
		self::$init = true;
	}

	/**
	 * Get the language class instance and set it if not set
	 *
	 * @return LangBase - Language class instance
	 */
	public static function out(): LangBase {
		if(self::getLanguage() === null) {
			self::init();
		}

		return self::getLanguage();
	}

	/**
	 * Outputs the current JS-Language File URI
	 *
	 * @return string - JS-Language File URI
	 */
	public static function getLangJsFileUri(): string {
		if(self::getLanguage() === null)
			self::init();

		return self::getLanguageJsDir() . basename(self::getCurrentLanguageFileName()) . '.js';
	}

	/**
	 * @return null|string
	 */
	private static function getCurrentLanguageFileName(): ?string {
		return self::$currentLanguageFileName;
	}

	/**
	 * @param string|null $currentLanguageFileName
	 */
	private static function setCurrentLanguageFileName(?string $currentLanguageFileName): void {
		self::$currentLanguageFileName = $currentLanguageFileName;
	}

	/**
	 * @return LangBase|null
	 */
	private static function getLanguage(): ?LangBase {
		return self::$language;
	}

	/**
	 * @param LangBase|null $language
	 */
	private static function setLanguage(?LangBase $language): void {
		self::$language = $language;
	}

	/**
	 * @return null|string
	 */
	private static function getLanguagePhpDir(): ?string {
		return self::$languagePhpDir;
	}

	/**
	 * @param string|null $languagePhpDir
	 */
	public static function setLanguagePhpDir(?string $languagePhpDir): void {
		self::$languagePhpDir = $languagePhpDir;
	}

	/**
	 * @return null|string
	 */
	private static function getLanguageJsDir(): ?string {
		return self::$languageJsDir;
	}

	/**
	 * @param string|null $languageJsDir
	 */
	public static function setLanguageJsDir(?string $languageJsDir): void {
		self::$languageJsDir = $languageJsDir;
	}

	/**
	 * Set the available Languages
	 *
	 * @param string[] $availableLanguages - Available Languages
	 */
	public static function setAvailableLanguages(array $availableLanguages): void {
		self::$availableLanguages = $availableLanguages;
	}

	/**
	 * Get the available Languages
	 *
	 * @return string[] - Available Languages
	 */
	private static function getAvailableLanguages(): array {
		return self::$availableLanguages;
	}

	/**
	 * Set the Language Cookie-Name
	 *
	 * @param string $langCookieName - Language Cookie-Name
	 */
	public static function setLangCookieName(string $langCookieName): void {
		self::$langCookieName = $langCookieName;
	}

	/**
	 * Get the Language Cookie-Name
	 *
	 * @return string - Language Cookie-Name
	 */
	private static function getLangCookieName(): string {
		return self::$langCookieName;
	}

	/**
	 * Get the default Language
	 *
	 * @return string - Default Language
	 */
	private static function getDefaultLang(): string {
		return self::$defaultLang;
	}

	/**
	 * Set the default Language
	 *
	 * @param string $defaultLang - Default Language
	 */
	public static function setDefaultLang(string $defaultLang): void {
		self::$defaultLang = $defaultLang;
	}

	/**
	 * Get the Language GET-Name
	 *
	 * @return string - Language GET-Name
	 */
	private static function getLangGetName(): string {
		return self::$langGetName;
	}

	/**
	 * Set the Language GET-Name
	 *
	 * @param string $langGetName - Language GET-Name
	 */
	public static function setLangGetName(string $langGetName): void {
		self::$langGetName = $langGetName;
	}

	/**
	 * Shows if Cookie-check is enabled
	 *
	 * @return bool - Is Cookie-Check enabled
	 */
	private static function isCookiesEnabled(): bool {
		return self::$cookiesEnabled;
	}

	/**
	 * Set if Cookie-check is enabled
	 *
	 * @param bool $cookiesEnabled - Is Cookie-Check enabled
	 */
	public static function setCookiesEnabled(bool $cookiesEnabled): void {
		self::$cookiesEnabled = $cookiesEnabled;
	}

	/**
	 * @return int
	 */
	private static function getCookieExpTime(): int {
		return self::$cookieExpTime;
	}

	/**
	 * @param int $cookieExpTime
	 */
	public static function setCookieExpTime(int $cookieExpTime): void {
		self::$cookieExpTime = $cookieExpTime;
	}

	/**
	 * Set/Refresh the Language Cookie
	 */
	private static function setLangCookie(): void {
		if(! self::isCookiesEnabled()) {
			return;
		}

		setcookie(self::getLangCookieName(), self::getCurrentLanguageFileName(), time() + self::getCookieExpTime());
	}

	/**
	 * Returns the Value of the User-Language Cookie or null if not set/allowed
	 *
	 * @return null|string - Language Cookie-Value
	 */
	private static function getLangCookieValue(): ?string {
		if(! self::isCookiesEnabled()) {
			return null;
		}

		return $_COOKIE[self::getLangCookieName()] ?? null;
	}

	/**
	 * Checks if the Language exists
	 *
	 * @param mixed $value - Value to check if exists
	 * @return bool - true if language is valid else false
	 */
	private static function validateLangValue($value): bool {
		return array_key_exists($value, self::getAvailableLanguages());
	}

	/**
	 * Includes the current Language PHP File
	 *
	 * @throws Exception - File not Found exception
	 */
	private static function requireLangClassFileOnce(): void {
		$file = self::getLanguagePhpDir() . DS . basename(self::getCurrentLanguageFileName()) . '.php';

		if(file_exists($file)) {
			require_once($file);
		} else {
			throw new Exception(__CLASS__ . '-File "' . $file . '" doesn\'t exists... Please fix this error by creating that file!');
		}
	}
}
