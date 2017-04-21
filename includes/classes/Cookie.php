<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 22.07.2015
 * Time: 21:07
 * Update: 02.05.2016
 * Version 1.1.0 (Added Setters and removed non-class content from class)
 * 1.0.3 (Changed Class-Name & Website)
 * 1.0.2 (Reformat Code - Fixed head doc)
 * @package Petschkos Framework
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 * Come to us we have cookies *.* Or Play Cookie-Clicker!^^
 *
 * How to use:
 * Set cookie only if its allowed -- cookie::setCookie(@vanilla_params) -> See http://php.net/setcookie for parameter use
 * Read cookie if its allowed -- cookie::getCookie((string)cookieName[, (mixed)fallback_value = false[, (bool)force_read = false]])
 * Check if cookies are allowed (for displaying msg or something like that) -- cookie::getIsAllowed()
 * Enable Cookies, if user clicked on accept button -- cookie::enableCookies()
 * Disable Cookies (for not showing cookie msg anymore or something like that),
 * but inform user that there IS one Cookie (MasterCookie) if he want never show message -- cookie::enableCookies(false)
 * (Very-Optional) Set the country of the User (for allow/deny cookies without asking in countries you have set) -- cookie::setCountry((string)country)
 *
 * You can make more configurations below (Marked with CONFIG AREA)
 */

/**
 * Class Cookie
 *
 * Static Object
 */
class Cookie {
	/**
	 * Contains the Ignore-Cookie-Police
	 *
	 * @var bool - ignore the cookie law - true will shut off the whole disable function
	 */
	private static $ignoreCookiePolice = false;

	/**
	 * Contains a List with Countries
	 *
	 * @var array - Country-List
	 */
	private static $countryList = array();

	/**
	 * Contains if the Country list is handled as white list for the cookie law (mean all this countries will ignore the law)
	 *
	 * @var bool - Is whiteList
	 */
	private static $countryModeWhiteList = true;

	/**
	 * Contains the Name of the Master-Cookie
	 *
	 * @var string - Master-Cookie-Name
	 */
	private static $masterCookieName = 'allow_cookies';

	/**
	 * Contains the Master-Cookie expire time in secs
	 * Default 1 Year from creation (counted in secs)
	 *
	 * @var int - Master-Cookie expire time
	 */
	private static $masterCookieExpireTime = 31536000;

	/**
	 * Contains the current user Country
	 *
	 * @var bool|string - User-Country or false if none is set
	 */
	private static $country = false;

	/**
	 * Contains if Cookies are allowed or not
	 *
	 * @var bool - Cookies are allowed
	 */
	private static $isAllowed;

	/**
	 * Cookie constructor.
	 */
	private function __construct() {}
	private function __clone() {}

	/**
	 * Sets a Cookie like the normal setCookie function ---- See documentation: http://php.net/setcookie
	 *
	 * @param string $name - Cookie Name
	 * @param string $value - Value of the cookie
	 * @param int $expire - Expire-Date in UNIX-Timestamp
	 * @param string|null $path - Cookie-Path on your Server
	 * @param string|null $domain - Cookie-Domain
	 * @param bool|null $secure - Send only if HTTPS
	 * @param bool|null $httpOnly - Cookie is only readable via HTTP-Protocol
	 * @return bool - true on success
	 */
	public static function setCookie($name, $value, $expire = 0, $path = null, $domain = null, $secure = null, $httpOnly = null) {
		if(self::cookiesAllowed() || self::isIgnoreCookiePolice())
			return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

		return false;
	}

	/**
	 * Get the value of a cookie (if allowed) or get a default value
	 *
	 * @param string $cookieName - Name of the Cookie
	 * @param mixed $defaultValue - Return value if deny read cookies
	 * @param bool $forceRead - Force-Read - Shows the cookie, no matter what, except it is not set
	 * @return mixed - Cookie-Value or $default_value
	 */
	public static function getCookie($cookieName, $defaultValue = false, $forceRead = false) {
		if($forceRead || self::cookiesAllowed()) {
			if(! isset($_COOKIE[$cookieName]))
				$_COOKIE[$cookieName] = '';

			return $_COOKIE[$cookieName];
		}

		return $defaultValue;
	}

	/**
	 * Checks the Master-Cookie if cookies are allowed
	 *
	 * @return bool - Are Cookies allowed - true if cookies are allowed to set/read
	 */
	private static function cookiesAllowed() {
		if(! isset($_COOKIE[self::getMasterCookieName()]))
			$_COOKIE[self::getMasterCookieName()] = false;

		if($_COOKIE[self::getMasterCookieName()] == 'allow' || self::isIgnoreCookiePolice() || self::allowCountryCookie())
			return true;

		return false;
	}

	/**
	 * Checks if the user is in an allowed country
	 *
	 * @return bool - true if country is allowed to use cookies without asking
	 */
	private static function allowCountryCookie() {
		if(self::isCountryModeWhiteList()) {
			foreach(self::getCountryList() as $country) {
				if($country == self::getCountry())
					return true;
			}

			// Default return value of whiteList
			return false;
		} else {
			foreach(self::getCountryList() as $country) {
				if($country == self::getCountry())
					return false;
			}

			// Default return value of blacklist
			return true;
		}
	}

	/**
	 * Enabled/Disabled Cookies and save it to the user by a Master-Cookie
	 *
	 * @param bool $enabled - Enable cookies, set this to false to disable them
	 */
	public static function enableCookies($enabled = true) {
		if($enabled)
			$value = 'allow';
		else
			$value = 'deny';

		// Set Master Cookie :3
		setcookie(self::getMasterCookieName(), $value, time() + self::getMasterCookieExpireTime());
	}

	/**
	 * Get the user Country or none if none is set
	 *
	 * @return bool|string - User Country
	 */
	public static function getCountry() {
		if(! self::$country)
			return 'None';

		return self::$country;
	}

	/**
	 * Set the User-Country
	 *
	 * @param string $country - Sets user country | recommend 2 letter country names
	 */
	public static function setCountry($country) {
		self::$country = $country;
	}

	/**
	 * Shows if Cookies are allowed
	 *
	 * @return boolean - Shows true if cookies are allowed
	 */
	public static function getIsAllowed() {
		if(! isset(self::$isAllowed))
			self::setIsAllowed(self::cookiesAllowed());

		return self::$isAllowed;
	}

	/**
	 * Set allowed to true/false
	 *
	 * @param bool $isAllowed - Is Allowed
	 */
	private static function setIsAllowed($isAllowed) {
		self::$isAllowed = $isAllowed;
	}

	/**
	 * Get the ignore CookiePolice
	 *
	 * @return boolean - Ignore-Police - True if Cookies are always allowed
	 */
	private static function isIgnoreCookiePolice() {
		return self::$ignoreCookiePolice;
	}

	/**
	 * Set the ignore CookiePolice
	 *
	 * @param boolean $ignoreCookiePolice - Ignore-Police - True if Cookies are always allowed
	 */
	public static function setIgnoreCookiePolice($ignoreCookiePolice) {
		self::$ignoreCookiePolice = $ignoreCookiePolice;
	}

	/**
	 * Get the CountryList
	 *
	 * @return array - List of countries
	 */
	private static function getCountryList() {
		return self::$countryList;
	}

	/**
	 * Set the Country-List
	 *
	 * @param array $countryList - Country-List-Array
	 */
	public static function setCountryList($countryList) {
		self::$countryList = $countryList;
	}

	/**
	 * Get the CountryList type (white/blacklist)
	 *
	 * @return boolean - List-Mode - true is whiteList mode and false is blacklist mode for countries
	 */
	public static function isCountryModeWhiteList() {
		return self::$countryModeWhiteList;
	}

	/**
	 * Set the CountryList type (white/blacklist)
	 *
	 * @param boolean $countryModeWhiteList - List-Mode - true is whiteList mode and false is blacklist mode for countries
	 */
	public static function setCountryModeWhiteList($countryModeWhiteList) {
		self::$countryModeWhiteList = $countryModeWhiteList;
	}

	/**
	 * Get the Name of the MasterCookie
	 *
	 * @return string - MasterCookie Name
	 */
	public static function getMasterCookieName() {
		return self::$masterCookieName;
	}

	/**
	 * Set the Name of the Master-Cookie
	 *
	 * @param string $masterCookieName - MasterCookie Name
	 */
	public static function setMasterCookieName($masterCookieName) {
		self::$masterCookieName = $masterCookieName;
	}

	/**
	 * Get the MasterCookie expire time (secs)
	 *
	 * @return int - The expire time of the masterCookie (secs)
	 */
	public static function getMasterCookieExpireTime() {
		return self::$masterCookieExpireTime;
	}

	/**
	 * Set the MasterCookie expire time (secs)
	 *
	 * @param int $masterCookieExpireTime - New MasterCookie expire time (secs)
	 */
	public static function setMasterCookieExpireTime($masterCookieExpireTime) {
		self::$masterCookieExpireTime = $masterCookieExpireTime;
	}
}
