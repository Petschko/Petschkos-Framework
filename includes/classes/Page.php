<?php

/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 19.06.2017
 * Time: 14:54
 *
 * Notes: -
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class Page
 */
class Page {
	/**
	 * @var string|null $baseUrl - Base URL of the Website
	 */
	private static $baseUrl = null;

	/**
	 * @var bool $prettyUrls - Are pretty urls enabled
	 */
	private static $prettyUrls = false;

	/**
	 * @var string $name - Internal Name of this Page
	 */
	private static $name;

	/**
	 * @var string $title - Title of this Page
	 */
	private static $title;

	/**
	 * @var string|null $websiteTitle - Global title of the Website
	 */
	private static $websiteTitle = null;

	/**
	 * @var string|null $metaTitle - Meta title of the Page
	 */
	private static $metaTitle = null;

	/**
	 * @var string|null $metaDesc - Meta description of the Page
	 */
	private static $metaDesc = null;

	/**
	 * @var string|null $metaKeywords - Meta Keywords of the Page
	 */
	private static $metaKeywords = null;

	/**
	 * @var string|null $canonicalLink - Canonical Link of the Page
	 */
	private static $canonicalLink = null;

	/**
	 * @var string $viewFile - View-File of the Page
	 */
	private static $viewFile;

	/**
	 * @var bool $cacheAble
	 */
	private static $cacheAble = false;

	/**
	 * @var int $cacheTimeSec
	 */
	private static $cacheTimeSec = 86400;

	/**
	 * @var array $cssFilesArray - CSS-Files of the Page
	 */
	private static $cssFilesArray = array();

	/**
	 * @var array $jsFilesArray - JavaScript-Files of the Page
	 */
	private static $jsFilesArray = array();

	/**
	 * Disabled Page constructor.
	 */
	private function __construct() {
		// VOID
	}

	/**
	 * Disabled Page Clone function
	 */
	private function __clone() {
		// VOID
	}

	/**
	 * @return string
	 * @throws Exception - Base URL not set
	 */
	public static function getBaseUrl() {
		if(self::$baseUrl === null)
			throw new Exception('Page-Base URL is not set in the ' . __CLASS__ . ' Class!');

		return self::$baseUrl;
	}

	/**
	 * @param string $baseUrl
	 */
	public static function setBaseUrl($baseUrl) {
		self::$baseUrl = $baseUrl;
	}

	/**
	 * @return bool
	 */
	public static function isPrettyUrls() {
		return self::$prettyUrls;
	}

	/**
	 * @param bool $prettyUrls
	 */
	public static function setPrettyUrls($prettyUrls) {
		self::$prettyUrls = $prettyUrls;
	}

	/**
	 * @return string
	 */
	public static function getName() {
		return self::$name;
	}

	/**
	 * @param string $name
	 */
	public static function setName($name) {
		self::$name = $name;
	}

	/**
	 * @return string
	 */
	public static function getTitle() {
		return self::$title;
	}

	/**
	 * @param string $title
	 */
	public static function setTitle($title) {
		self::$title = $title;
	}

	/**
	 * @return null|string
	 */
	public static function getWebsiteTitle() {
		return self::$websiteTitle;
	}

	/**
	 * @param null|string $websiteTitle
	 */
	public static function setWebsiteTitle($websiteTitle) {
		self::$websiteTitle = $websiteTitle;
	}

	/**
	 * @return null|string
	 */
	public static function getMetaTitle() {
		return self::$metaTitle;
	}

	/**
	 * @param null|string $metaTitle
	 */
	public static function setMetaTitle($metaTitle) {
		self::$metaTitle = $metaTitle;
	}

	/**
	 * @return null|string
	 */
	public static function getMetaDesc() {
		return self::$metaDesc;
	}

	/**
	 * @param null|string $metaDesc
	 */
	public static function setMetaDesc($metaDesc) {
		self::$metaDesc = $metaDesc;
	}

	/**
	 * @return null|string
	 */
	public static function getMetaKeywords() {
		return self::$metaKeywords;
	}

	/**
	 * @param null|string $metaKeywords
	 */
	public static function setMetaKeywords($metaKeywords) {
		self::$metaKeywords = $metaKeywords;
	}

	/**
	 * @return null|string
	 */
	public static function getCanonicalLink() {
		return self::$canonicalLink;
	}

	/**
	 * @param null|string $canonicalLink
	 */
	public static function setCanonicalLink($canonicalLink) {
		self::$canonicalLink = $canonicalLink;
	}

	/**
	 * @return string
	 */
	public static function getViewFile() {
		return self::$viewFile;
	}

	/**
	 * @param string $viewFile
	 */
	public static function setViewFile($viewFile) {
		self::$viewFile = $viewFile;
	}

	/**
	 * @return bool
	 */
	public static function isCacheAble() {
		return self::$cacheAble;
	}

	/**
	 * @param bool $cacheAble
	 */
	public static function setCacheAble($cacheAble) {
		self::$cacheAble = $cacheAble;
	}

	/**
	 * @return int
	 */
	public static function getCacheTimeSec() {
		return self::$cacheTimeSec;
	}

	/**
	 * @param int $cacheTimeSec
	 */
	public static function setCacheTimeSec($cacheTimeSec) {
		self::$cacheTimeSec = $cacheTimeSec;
	}

	/**
	 * @return array
	 */
	private static function getCssFilesArray() {
		return self::$cssFilesArray;
	}

	/**
	 * @return array
	 */
	private static function getJsFilesArray() {
		return self::$jsFilesArray;
	}

	/**
	 * Prints the Canonical-Link within HTML
	 */
	public static function printHtmlCanonicalLink() {
		if(! self::getCanonicalLink())
			return;

		echo '		<link rel="canonical" href="' . self::getCanonicalLink() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Meta-Title within HTML
	 */
	public static function printHtmlMetaTitle() {
		if(! self::getMetaTitle())
			return;

		echo '		<meta name="title" content="' . self::getMetaTitle() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Meta-Description within HTML
	 */
	public static function printHtmlMetaDescription() {
		if(! self::getMetaDesc())
			return;

		echo '		<meta name="description"  content="' . self::getMetaDesc() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Meta-Keywords within HTML
	 */
	public static function printHtmlMetaKeywords() {
		if(! self::getMetaKeywords())
			return;

		echo '		<meta name="keywords"  content="' . self::getMetaKeywords() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Page-Title plus the Website-Title if set
	 */
	public static function printWebsiteTitle() {
		echo  self::getTitle() . ((self::getWebsiteTitle()) ? ' - ' . self::getWebsiteTitle() : '');
	}

	/**
	 * Adds a CSS-File to the current Page
	 *
	 * @param string $file - File to add
	 * @param bool $addInHead - Add File in Head false add it before </body>
	 */
	public static function addCssFile($file, $addInHead = true) {
		self::$cssFilesArray[] = array($file, $addInHead);
	}

	/**
	 * Adds a JS-File to the current Page
	 *
	 * @param string $file - File to add
	 * @param bool $addInHead - Add File in Head false add it before </body>
	 * @param bool $async - Loads the JS-File Async
	 */
	public static function addJSFile($file, $addInHead = true, $async = false) {
		self::$jsFilesArray[] = array($file, $addInHead, $async);
	}

	/**
	 * Print all CSS-Files as HTML-Include-String
	 *
	 * @param bool $headFiles - Print Head-Files
	 */
	public static function printCssFiles($headFiles) {
		foreach(self::getCssFilesArray() as &$cssFile) {
			if($cssFile[1] === $headFiles)
				echo '		<link rel="stylesheet" href="' . $cssFile[0] . '" />' . PHP_EOL;
		}
	}

	/**
	 * Print all JS-Files as HTML-Include-String
	 *
	 * @param bool $headFiles - Print Head-Files
	 */
	public static function printJsFiles($headFiles) {
		foreach(self::getJsFilesArray() as &$jsFile) {
			if($jsFile[1] === $headFiles)
				echo '		<script ' . (($jsFile[2]) ? 'async ' : '') . 'src="' . $jsFile[0] . '"></script>' . PHP_EOL;
		}
	}

	/**
	 * Check if the Nav-Page is the current page
	 *
	 * @param string $navPage - Nav-Page to check
	 */
	public static function navCssCheckPage($navPage) {
		if(mb_strtolower($navPage) === mb_strtolower(self::getName()))
			echo ' active';
	}

	/**
	 * Prints the Page-URL of the requested Page
	 *
	 * @param string $page - Page to get URL from
	 * @param bool $print - Print direct
	 * @return string - Page-URL
	 */
	public static function getFullPageUrl($page, $print = true) {
		$pageUrl = self::getBaseUrl();

		if($page) {
			if(self::isPrettyUrls())
				$pageUrl .= $page . '/';
			else
				$pageUrl .= 'index.php?page=' . $page;
		}

		if($print)
			echo $pageUrl;

		return $pageUrl;
	}

	/**
	 * Prints out the Year(s) of the Page, since it was launched
	 *
	 * @param int $startYear - Start Year of the Page
	 */
	public static function printPageSince($startYear = 2017) {
		$currentYear = date('Y', time());

		if($startYear < $currentYear)
			echo $startYear . ' - ' . $currentYear;
		else
			echo $startYear;
	}

	/**
	 * Prints the AJAX-URL for the Action
	 *
	 * @param string $action - Action for the AJAX-Request
	 */
	public static function printAjaxURL($action) {
		$ajaxUrl = self::getBaseUrl();

		if(self::isPrettyUrls())
			$ajaxUrl .= 'ajax/' . urlencode($action) . '/';
		else
			$ajaxUrl .= urlencode('index.php?page=ajax&action=' . $action);

		echo '\'' . $ajaxUrl . '\'';
	}
}
