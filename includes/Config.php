<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 28.12.2015 (RAW FILE)
 * Time: 19:36 (RAW FILE)
 *
 * Notes: Contains Config stuff
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class Config
 *
 * Static-Object
 */
class Config {
	// System-Stuff
	public const VERSION = 'v3.0.0';

	// Mysql settings
	public const DB_ENABLED = false;								// Is the DB-Support enabled?
	public const DB_TYPE = 'mysql';									// Type of the Database-Server (MySQL, SQLite etc)
	public const DB_HOST = '127.0.0.1';								// Hostname/IP of the Database-Server
	public const DB_PORT = 3306;									// Port of the Database-Server
	public const DB_USER = 'root';									// Database User
	public const DB_PASSWORD = 'root';								// Password of the Database User
	public const DB_NAME = 'website';								// Name of the Database
	public const DB_TABLE_PREFIX = '';								// Prefix of the tables and don't forget the _!! (if there is no prefix, let this empty)
	public const DB_CHARSET = 'utf8';								// Database Charset
	public const DB_CHARSET_COLLATION = 'utf8mb4_unicode_ci';		// Database Collation

	// Cookie Settings
	public const COOKIE_POLICE_SET = false;							// Set cookie law, false means cookies will only set if the user accept it, after clicking on "allow set cookies", true set normal cookies without asking. in Europe better set this to false
	public const COOKIE_POLICE_COUNTRY_WHITELIST_MODE = true;		// true = Never ask for cookies in the selected countries | false = Only ask on the selected countries for cookies

	// Anti-Spam
	public const ANTI_SPAM_ENABLED = true;							// Uses Anti-spam methods to avoid spam (no captcha needed)
	public const ANTI_SPAM_NUMBERS_AS_TEXT = true;					// Display numbers as words instead of numbers
	public const ANTI_SPAM_USE_MATH_SYMBOLS = false;				// Use Symbols instead of words for operators
	public const ANTI_SPAM_MAX_ATTEMPTS = 5;						// How often a honeypots need to be filled out before user is blocked
	public const ANTI_SPAM_RECAPTCHA_AFTER_MAX_ATTEMPTS = true;		// Use Re-Captcha (if enabled) on max attempts is reached - Else user can't post anything

	// ReCaptcha Settings - Ignore them if you don't use this
	public const RECAPTCHA_ENABLE = false;							// Google-ReCaptcha Enabled (false = off | true = on)
	public const RECAPTCHA_PUBLIC_KEY = '';							// Google-ReCaptcha Public-Key
	public const RECAPTCHA_PRIVATE_KEY = '';						// Google-ReCaptcha Private-Key

	// Google-Analytics Settings - Ignore them if you don't use this
	public const GOOGLE_ANALYTICS_ENABLE = false;					// Enable Google-Analytics (false = no | true = yes)
	public const GOOGLE_ANALYTICS_PROPERTY = '';					// Add your Google-Analytics Property here
	public const GOOGLE_ANALYTICS_ANONYMIZE_IP = true;				// Would you like to anonymize the IP-Address of the user before send it to Google? (true = yes | false = no)

	// Locale settings
	public const LANGUAGE_DEFAULT = 'lang.en';						// Default language (Specify the Filename without ext)
	public static $enabledLanguages = [
		/* Enabled languages
		 * Names are like the Language PHP/JS Files without extension
		 * (lang.de.php => lang.de, lang.en.php => lang.en and so on)
		 *
		 * Example:
		 * 'lang.de' => 'LangDe'
		 * 'filename without ext' => 'LangClass Name'
		 */
		'lang.de'				=> 'LangDe',
		'lang.de.formal'		=> 'LangDeFormal',
		'lang.en'				=> 'LangEn'
	];

	// Cache Settings
	public const CACHE_ENABLED = true;								// Enable or disables the Cache
	public const CACHE_LIFETIME_SEC = null;							// Set how long in Sec a Cache-File can exists (null uses default value of 1 Day)

	// Misc Settings
	public const sendMailEnabled = false;							// Can this site send E-Mails? (false = no | true = yes)
	public const adminName = 'Admin';								// Set Admin-Name
	public const adminEmail = 'admin@domain.tld';					// Set Admin-Email

	public const WEBSITE_TITLE = 'Title';							// Set Website-Title
	public const WEBSITE_CHARSET = 'UTF-8';							// HTML-Default Charset (UTF-8 recommend)
	public const WEBSITE_BASE_URL = 'http://localhost/';			// Page Base-URL (Needed for canonical links and many other) end with /
	public const WEBSITE_FAVICON = false;							// Set if you have a Favicon
	public const WEBSITE_PRETTY_URLS = false;						// Pretty URLs are enabled (Set up the HTACCESS or Webserver-Config by yourself) index.php?page=$page -> /$page/
	public const WEBSITE_SINCE_YEAR = 2022;							// Set the Year when the page was launched

	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * Disabled Config constructor.
	 */
	private function __construct() {}

	/**
	 * Disabled Config Clone
	 */
	private function __clone() {}
}
