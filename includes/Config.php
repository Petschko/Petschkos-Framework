<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 28.12.2015 (RAW FILE)
 * Time: 19:36 (RAW FILE)
 * Update: 09.04.2016 (RAW FILE)
 * Version: 1.0.6 (Make Private constructor and clone and removed old config stuff)
 * 1.0.5 (Changed Class-Name & Website) @ (RAW FILE)
 * 1.0.4 (Reformat Code) @ (RAW FILE)
 * @package Petschkos Framework
 *
 * Notes: Contains Config stuff
 */

/**
 * Class Config
 *
 * Static-Object
 */
class Config {
	// Mysql settings
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

	// Locale settings
	const defaultLang = 'Deutsch';							// Default language
	public static $enabledLanguages = array(
		// Enabled languages (names are like the Language php-files (Deutsch.php => Deutsch, English.php => English and so on)
		'Deutsch',
		'English'
	);

	// Misc Settings
	const adminName = 'Admin';								// Set Admin-Name
	const adminEmail = 'admin@domain.tld';					// Set Admin-Email
	const websiteTitle = 'Title';							// Set Website-Title
	const charset = 'UTF-8';								// HTML-Default Charset (UTF-8 recommend)

	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * Config constructor.
	 */
	private function __construct() {}
	private function __clone() {}
}
