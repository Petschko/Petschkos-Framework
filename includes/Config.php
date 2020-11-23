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
	const version = 'v2.0.1';

	// Mysql settings
	const dbEnabled = false;								// Is the DB-Support enabled?
	const dbType = 'mysql';									// Type of the Database-Server (MySQL, SQLite etc)
	const dbHost = '127.0.0.1';								// Hostname/IP of the Database-Server
	const dbPort = 3306;									// Port of the Database-Server
	const dbUser = 'root';									// Database User
	const dbPassword = 'root';								// Password of the Database User
	const dbWebsiteDb = 'website';							// Name of the Database
	const dbTablePref = '';									// Prefix of the tables and don't forget the _!! (if there is no prefix, let this empty)
	const dbCharset = 'utf8';								// Database Charset
	const dbCharsetCollation = 'utf8mb4_unicode_ci';		// Database Collation

	// Cookie Settings
	const cookiePoliceSet = false;							// Set cookie law, false means cookies will only set if the user accept it, after clicking on "allow set cookies", true set normal cookies without asking. in Europe better set this to false
	const cookiePoliceCountryModeWhiteList = true;			// true = Never ask for cookies in the selected countries | false = Only ask on the selected countries for cookies

	// ReCaptcha Settings - Ignore them if you don't use this
	const enableCaptcha = false;							// Google-ReCaptcha Enabled (false = no | true = yes)
	const publicKey = '';									// Google-ReCaptcha Public-Key
	const privateKey = '';									// Google-ReCaptcha Private-Key

	// Google-Analytics Settings - Ignore them if you don't use this
	const enableGoogleAnalytics = false;					// Enable Google-Analytics (false = no | true = yes)
	const gaProperty = '';									// Add your Google-Analytics Property here
	const gaAnonymizeIp = true;								// Would you like to anonymize the IP-Address of the user before send it to Google? (true = yes | false = no)

	// Locale settings
	const defaultLang = 'lang.en';							// Default language (Specify the Filename without ext)
	public static $enabledLanguages = array(
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
	);

	// Cache Settings
	const cacheEnabled = true;								// Enable or disables the Cache
	const cacheFileLifeTime = null;							// Set how long in Sec a Cache-File can exists (null uses default value of 1 Day)

	// Misc Settings
	const sendMailEnabled = false;							// Can this site send E-Mails? (false = no | true = yes)
	const adminName = 'Admin';								// Set Admin-Name
	const adminEmail = 'admin@domain.tld';					// Set Admin-Email
	const websiteTitle = 'Title';							// Set Website-Title
	const charset = 'UTF-8';								// HTML-Default Charset (UTF-8 recommend)
	const pageBaseURL = 'http://localhost/';				// Page Base-URL (Needed for canonical links and many other) end with /
	const favicon = false;									// Set if you have a Favicon
	const prettyUrls = false;								// Pretty URLs are enabled (Setup the HTACCESS or Webserver-Config by yourself) index.php?page=$page -> /$page/
	const pageSince = 2020;									// Set the Year where the page was launched

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
