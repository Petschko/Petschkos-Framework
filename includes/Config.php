<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 28.12.2015 (RAW FILE)
 * Time: 19:36 (RAW FILE)
 * Update: 09.04.2016 (RAW FILE)
 * Version: 1.0.5 (Changed Class-Name & Website) @ (RAW FILE)
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
	const defaultLang = 'en';								// Default language
	public static $enabledLanguages = array('de', 'en');	// Enabled languages (names are like the Language php-files (de.php => de, en.php => en and so on)

	// Misc Settings
	const adminName = 'Admin';								// Set Admin-Name
	const adminEmail = 'admin@domain.tld';					// Set Admin-Email
	const websiteTitle = 'Title';							// Set Website-Title
	const errorClass = 'form_msg_error';					// Set class for css for error messages
	const successClass = 'form_msg_success';				// Set class for css for success messages
	const websiteSince = 2012;								// Set launch year of the website
	const charset = 'UTF-8';								// HTML-Default Charset (UTF-8 recommend)

	// Captcha Settings
	const captchaEnabled = false;							// Enable/Disable Captcha
	const captchaInverted = false;							// On true the user has to type in the input inverted
	const captchaMinLen = 3;								// Captcha min solve Chars
	const captchaMaxLen = 5;								// Captcha max solve Chars
	const captchaFontSize = 16;								// Set the img font size for the Captcha Chars
	const captchaImgHeight = 51;							// Set the image height in pixels
	const captchaImgWidth = 185;							// Set the image width in pixels
	public static $captchaBgColor = array(0, 0, 0);			// Set Captcha Background-Color (Red 0 - 255, Green 0 - 255, Blue 0 - 255, Transparency 0 - 127)
	const captchaFontType = 'arial.ttf';					// Set the font file for the Captcha image
	const captchaIgnoreCharsColors = 0;						// Set how many different ignore-char-colors appears. 0 means ignore-chars are disabled todo
	const captchaEnableRandomFragments = false;				// On true: Generate random lines and other tiny fragments to make automatic detection difficult todo
	const captchaMath = false;								// On true: Not use characters for detection, use math task todo
	public static $numMax = 9;								// todo math_settings

	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * Constructor disabled
	 */
	private function __construct() {}
}
