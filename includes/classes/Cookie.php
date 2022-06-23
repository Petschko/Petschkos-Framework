<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: https://petschko.org/
 * Date: 22.07.2015
 * Time: 21:07
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Come to us, we have cookies *.* or Play Cookie-Clicker!^^
 *
 * Notes: Contains the Cookie-Class
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class Cookie - Only use this class to set tracking cookies
 *
 * Static Object
 */
class Cookie {
	/**
	 * None-Country Constant
	 */
	public const COUNTRY_NONE = 'None';

	/**
	 * Contains the Ignore-Cookie-Police
	 *
	 * @var bool - ignore the cookie law - true will shut off the whole disable function
	 */
	private static bool $ignoreCookiePolice = false;

	/**
	 * Contains a List with Countries
	 *
	 * @var string[] - Country-List
	 */
	private static array $countryList = [];

	/**
	 * Contains if the Country list is handled as white list for the cookie law (mean all this countries will ignore the law)
	 *
	 * @var bool - Is whiteList
	 */
	private static bool $countryModeWhiteList = true;

	/**
	 * Contains the Name of the Master-Cookie
	 *
	 * @var string - Master-Cookie-Name
	 */
	private static string $masterCookieName = 'allow_cookies';

	/**
	 * Contains the Master-Cookie expire time in secs
	 * Default 1 Year from creation (counted in secs)
	 *
	 * @var int - Master-Cookie expire time
	 */
	private static int $masterCookieExpireTime = 31536000;

	/**
	 * Contains the current user Country
	 *
	 * @var null|string - User-Country or null if none is set
	 */
	private static ?string $country = null;

	/**
	 * Contains if Cookies are allowed or not
	 *
	 * @var bool - Cookies are allowed
	 */
	private static bool $isAllowed;

	/**
	 * Cookie constructor.
	 */
	private function __construct() {}
	private function __clone() {}

	/**
	 * Sets a Cookie like the normal setCookie function ---- See documentation: https://php.net/setcookie
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
	public static function setCookie(
		string $name,
		string $value,
		int $expire = 0,
		?string $path = null,
		?string $domain = null,
		?bool $secure = null,
		?bool $httpOnly = null
	): bool {
		if(self::cookiesAllowed() || self::isIgnoreCookiePolice()) {
			return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
		}

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
	public static function getCookie(string $cookieName, $defaultValue = false, bool $forceRead = false) {
		if($forceRead || self::cookiesAllowed()) {
			if(! isset($_COOKIE[$cookieName])) {
				$_COOKIE[$cookieName] = null;
			}

			return $_COOKIE[$cookieName];
		}

		return $defaultValue;
	}

	/**
	 * Checks the Master-Cookie if cookies are allowed
	 *
	 * @return bool - Are Cookies allowed - true if cookies are allowed to set/read
	 */
	private static function cookiesAllowed(): bool {
		if(! isset($_COOKIE[self::getMasterCookieName()])) {
			$_COOKIE[self::getMasterCookieName()] = false;
		}

		if($_COOKIE[self::getMasterCookieName()] || self::isIgnoreCookiePolice() || self::allowCountryCookie()) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if the user is in an allowed country
	 *
	 * @return bool - true if country is allowed to use cookies without asking
	 */
	private static function allowCountryCookie(): bool {
		if(self::isCountryModeWhiteList()) {
			foreach(self::getCountryList() as $country) {
				if($country === self::getCountry()) {
					return true;
				}
			}

			// Default return value of whiteList
			return false;
		}

		foreach(self::getCountryList() as $country) {
			if($country === self::getCountry()) {
				return false;
			}
		}

		// Default return value of blacklist
		return true;
	}

	/**
	 * Enabled/Disabled Cookies and save it to the user by a Master-Cookie
	 *
	 * @param bool $enabled - Enable cookies, set this to false to disable them
	 */
	public static function enableCookies(bool $enabled = true): void {
		// Set Master Cookie :3
		setcookie(self::getMasterCookieName(), $enabled ? '1' : '0', time() + self::getMasterCookieExpireTime());
	}

	/**
	 * Get the user Country or none if none is set
	 *
	 * @return string - User Country
	 */
	public static function getCountry(): string {
		if(! self::$country) {
			return self::COUNTRY_NONE;
		}

		return self::$country;
	}

	/**
	 * Set the User-Country
	 *
	 * @param string $country - Sets user country | recommend 2 letter country names
	 */
	public static function setCountry(string $country): void {
		self::$country = $country;
	}

	/**
	 * Shows if Cookies are allowed
	 *
	 * @return boolean - Shows true if cookies are allowed
	 */
	public static function getIsAllowed(): bool {
		if(! isset(self::$isAllowed))
			self::setIsAllowed(self::cookiesAllowed());

		return self::$isAllowed;
	}

	/**
	 * Set allowed true/false
	 *
	 * @param bool $isAllowed - Is Allowed
	 */
	private static function setIsAllowed(bool $isAllowed): void {
		self::$isAllowed = $isAllowed;
	}

	/**
	 * Get ignore CookiePolice
	 *
	 * @return bool - Ignore-Police - True if Cookies are always allowed
	 */
	private static function isIgnoreCookiePolice(): bool {
		return self::$ignoreCookiePolice;
	}

	/**
	 * Set ignore CookiePolice
	 *
	 * @param bool $ignoreCookiePolice - Ignore-Police - True if Cookies are always allowed
	 */
	public static function setIgnoreCookiePolice(bool $ignoreCookiePolice): void {
		self::$ignoreCookiePolice = $ignoreCookiePolice;
	}

	/**
	 * Get the CountryList
	 *
	 * @return string[] - List of countries
	 */
	private static function getCountryList(): array {
		return self::$countryList;
	}

	/**
	 * Set the Country-List
	 *
	 * @param string[] $countryList - Country-List-Array
	 */
	public static function setCountryList(array $countryList): void {
		self::$countryList = $countryList;
	}

	/**
	 * Get the CountryList type (white/blacklist)
	 *
	 * @return bool - List-Mode - true is whiteList mode and false is blacklist mode for countries
	 */
	public static function isCountryModeWhiteList(): bool {
		return self::$countryModeWhiteList;
	}

	/**
	 * Set the CountryList type (white/blacklist)
	 *
	 * @param bool $countryModeWhiteList - List-Mode - true is whiteList mode and false is blacklist mode for countries
	 */
	public static function setCountryModeWhiteList(bool $countryModeWhiteList): void {
		self::$countryModeWhiteList = $countryModeWhiteList;
	}

	/**
	 * Get the Name of the MasterCookie
	 *
	 * @return string - MasterCookie Name
	 */
	public static function getMasterCookieName(): string {
		return self::$masterCookieName;
	}

	/**
	 * Set the Name of the Master-Cookie
	 *
	 * @param string $masterCookieName - MasterCookie Name
	 */
	public static function setMasterCookieName(string $masterCookieName): void {
		self::$masterCookieName = $masterCookieName;
	}

	/**
	 * Get the MasterCookie expire time (secs)
	 *
	 * @return int - The expiry time of the masterCookie (secs)
	 */
	public static function getMasterCookieExpireTime(): int {
		return self::$masterCookieExpireTime;
	}

	/**
	 * Set the MasterCookie expire time (secs)
	 *
	 * @param int $masterCookieExpireTime - New MasterCookie expire time (secs)
	 */
	public static function setMasterCookieExpireTime(int $masterCookieExpireTime): void {
		self::$masterCookieExpireTime = $masterCookieExpireTime;
	}
}
