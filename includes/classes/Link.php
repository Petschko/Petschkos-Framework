<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 19.11.2015
 * Time: 23:46
 * Update: 12.04.2016
 * Version: 1.0.4 (Added class vars Doc)
 * 1.0.3 (Changed Class-Name & Website)
 * 1.0.2 (Reformat Code)
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * This script help you to hide Links within the EU, you can setup multiply things:
 * link::setDisabledLinkText(string $text) = Setup the message, if the Link is not shown
 * link::setEnableLinksAllTime(true/false) = true will ALWAYS shown ALL Links, false will check User Country & Link visibility
 * var $countries = Setup the country List, have can save them in whatever format you like... eg: array("de", "en"); or array("Germany", "England"); this depend on a script how you detect the country
 * var $whiteList = Setup the List-Mode, white-list (true) will ONLY show ALWAYS links to countries that are in the country-list, false will show ALWAYS links to countries they are NOT in the country list
 * link::setUserCountry(string $country) = Set the current user country, set this to false or none will ignore countries
 *
 * How to setup a Link
 * 1. You can create a new Link object and display the link
 * $var = new link($href[, string $linkText = false[, bool $alwaysVisible = true[, string $htmlAttributes = false]]]);
 * echo $var->show();
 *
 * 2. Or create a new object and show it instant
 * echo link::showNew($href[, string $linkText = false[, bool $alwaysVisible = true[, string $htmlAttributes = false]]]);
 */

/**
 * Class Link
 */
class Link {
	/**
	 * Message that will be show if Link is Hidden
	 *
	 * @var string - Disabled Link Text
	 */
	private static $disabledLinkText = 'Link disabled....';

	/**
	 * Holds if Links are always shown
	 * true = Always show ALL Links
	 * false = Show only links outside the affected countries and the links that are always visible
	 *
	 * @var bool - Enable Links all time
	 */
	private static $enableLinksAllTime = true;

	/**
	 * Holds a Country-List
	 *
	 * @var array - Country-List
	 */
	private static $countries = array();

	/**
	 * Holds the List-Mode
	 * true = The Links will ONLY shown ALWAYS in Countries that are IN the Country-Array
	 * false = The Links will ONLY shown ALWAYS in Countries they are NOT IN the Country-Array
	 *
	 * @var bool - List-Mode
	 */
	private static $whiteList = false;

	/**
	 * Holds the current Country of the User
	 *
	 * @var bool|mixed - User-Country
	 */
	private static $userCountry = false; // Do not touch this

	/**
	 * Holds the Link-Address
	 *
	 * @var string - Link-Address
	 */
	private $href;

	/**
	 * Holds the Link-Text
	 *
	 * @var string Link-Text
	 */
	private $text;

	/**
	 * Holds if the Link is always visible
	 *
	 * @var bool - Is the Link always visible
	 */
	private $alwaysVisible;

	/**
	 * Holds all other HTML-Attributes
	 *
	 * @var string - Other HTML-Attributes
	 */
	private $htmlAttr;

	/**
	 * Create a new Instance of a Link class
	 *
	 * @param string $href - The url you want to link - eg http://www.google.com/
	 * @param bool|string $text - LinkText (between <a></a>) - Set this on false will use the link as text
	 * @param bool $alwaysVisible - Set if link is always shown, set it to true even if your global settings disallow links (May if you have right to setup this link) else set this on false!!!
	 * @param bool|string $htmlAttr - Additional HTML attributes like -> class="link" or -> id="myLink" or some other html attributes
	 */
	public function __construct($href, $text = false, $alwaysVisible = true, $htmlAttr = false) {
		$this->setHref($href);
		$this->setText($text);
		$this->setAlwaysVisible($alwaysVisible);
		$this->setHtmlAttr($htmlAttr);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->href);
		unset($this->text);
		unset($this->alwaysVisible);
		unset($this->htmlAttr);
	}

	/**
	 * Get the disabled Link message
	 *
	 * @return string - The Link-Hidden message
	 */
	public static function getDisabledLinkText() {
		return self::$disabledLinkText;
	}

	/**
	 * Set the disabled Link message
	 *
	 * @param string $disabledLinkText - The Text that will be shown if the link is hidden
	 */
	public static function setDisabledLinkText($disabledLinkText) {
		self::$disabledLinkText = $disabledLinkText;
	}

	/**
	 * Shows if the link is always visible
	 *
	 * @return boolean - true = link is always visible | false = only if specified or not in the country list
	 */
	private function isAlwaysVisible() {
		return $this->alwaysVisible;
	}

	/**
	 * Set this link is always visible (or not)
	 *
	 * @param boolean $alwaysVisible - true = link is always visible | false = only if specified or not in the country list
	 */
	private function setAlwaysVisible($alwaysVisible) {
		$this->alwaysVisible = $alwaysVisible;
	}

	/**
	 * Get the current country of the User
	 *
	 * @return string|bool - Country of the current user
	 */
	private static function getUserCountry() {
		return self::$userCountry;
	}

	/**
	 * Set the current country of the user
	 *
	 * @param string|bool $userCountry - Set the country of the current user - false means ignore country
	 */
	public static function setUserCountry($userCountry) {
		self::$userCountry = $userCountry;
	}

	/**
	 * Get country List
	 *
	 * @return array - Country List
	 */
	private static function getCountries() {
		return self::$countries;
	}

	/**
	 * Check if the current List-Mode is whiteList or blackList
	 *
	 * @return boolean - True = White-List-Mode | False = Black-List-Mode
	 */
	private static function isWhiteList() {
		return self::$whiteList;
	}

	/**
	 * Returns the Link (or not^^)
	 *
	 * @return string - Link or Notice about hidden Link
	 */
	public function show() {
		if(! $this->getHref())
			return 'INVALID LINK -> Set href!!';

		// Set Links as Text if no text is set
		if(! $this->getText())
			$this->setText($this->getHref());

		if($this->checkVisibility())
			return '<a href="' . $this->getHref() . '"' . (($this->getHtmlAttr()) ? ' ' . $this->getHtmlAttr() : '') . '>' . $this->getText() . '</a>';
		else
			return '<span class="linkHidden">' . self::getDisabledLinkText() . '</span>';
	}

	/**
	 * Create a new Instance of a Link class - and shows direct the link
	 *
	 * @param string $href - The url you want to link - eg http://www.google.com/
	 * @param bool|string $text - LinkText (between <a></a>) - Set this on false will use the link as text
	 * @param bool $alwaysVisible - Set if link is always shown, set it to true even if your global settings disallow links (May if you have right to setup this link) else set this on false!!!
	 * @param bool|string $htmlAttr - Additional HTML attributes like -> class="link" or -> id="myLink" or some other html attributes
	 * @return string - The Link (Or not^^)
	 */
	public static function showNew($href, $text = false, $alwaysVisible = true, $htmlAttr = false) {
		$link = new self($href, $text, $alwaysVisible, $htmlAttr);
		return $link->show();
	}

	/**
	 * Check if the Link can seen by the User
	 *
	 * @return bool - true = yes | false = no
	 */
	private function checkVisibility() {
		// Check if it is always enabled for ALL
		if(self::isEnableLinksAllTime())
			return true;

		// Check country
		if(self::checkCountryVisibility())
			return true;

		// Check if link itself is visible if al other not allows to see it...
		if($this->isAlwaysVisible())
			return true;

		// Link is not visible to the user
		return false;
	}

	/**
	 * Check if users Country can always see links
	 *
	 * @return bool - true = yes | false = no
	 */
	private static function checkCountryVisibility() {
		// If none UserCountry is set ignore it
		if(! self::getUserCountry())
			return false;

		foreach(self::getCountries() as $country) {
			// Check if userCountry is in List
			if($country == self::getUserCountry()) {

				// Return correct value by ListType
				if(self::isWhiteList())
					return true;
				else
					return false;
			}
		}

		// If user Country is not in list return correct value by List-Type
		if(self::isWhiteList())
			return false;
		else
			return true;
	}

	/**
	 * Check if ALL links are visible
	 *
	 * @return boolean - True = always show ALL links | False = Show only links outside the affected countries and the links that are always visible
	 */
	private static function isEnableLinksAllTime() {
		return self::$enableLinksAllTime;
	}

	/**
	 * Set if ALL links are visible
	 *
	 * @param boolean $enableLinksAllTime - True = always show ALL links | False = Show only links outside the affected countries and the links that are always visible
	 */
	public static function setEnableLinksAllTime($enableLinksAllTime) {
		self::$enableLinksAllTime = $enableLinksAllTime;
	}

	/**
	 * Get the URL of this link
	 *
	 * @return string - Link-URL
	 */
	private function getHref() {
		if(! isset($this->href))
			return false;

		return $this->href;
	}

	/**
	 * Set the URL of this link
	 *
	 * @param string $href - Link-URL
	 */
	private function setHref($href) {
		$this->href = $href;
	}

	/**
	 * Get the Link-Text (Text between <a> and </a>)
	 *
	 * @return string - LinkText
	 */
	private function getText() {
		if(! $this->text)
			$this->setText($this->getHref());

		return $this->text;
	}

	/**
	 * Set the Link-Text (Text between <a> and </a>)
	 *
	 * @param bool|string $text - LinkText - false for URL as Text
	 */
	private function setText($text) {
		$this->text = $text;
	}

	/**
	 * Get additional HTML-Attributes
	 *
	 * @return bool|string - HTML attributes - false means none
	 */
	private function getHtmlAttr() {
		return $this->htmlAttr;
	}

	/**
	 * Set additional HTML-Attributes
	 *
	 * @param bool|string $htmlAttr - HTML attributes - false means none
	 */
	private function setHtmlAttr($htmlAttr) {
		$this->htmlAttr = $htmlAttr;
	}
}
