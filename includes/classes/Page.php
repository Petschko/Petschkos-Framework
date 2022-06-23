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
	private static ?string $baseUrl = null;

	/**
	 * @var bool $prettyUrls - Are pretty urls enabled
	 */
	private static bool $prettyUrls = false;

	/**
	 * @var string $name - Internal Name of this Page
	 */
	private static string $name;

	/**
	 * @var string $page - _GET Page-String
	 */
	private static string $page;

	/**
	 * @var string $title - Title of this Page
	 */
	private static string $title;

	/**
	 * @var string|null $websiteTitle - Global title of the Website
	 */
	private static ?string $websiteTitle = null;

	/**
	 * @var string|null $metaTitle - Meta title of the Page
	 */
	private static ?string $metaTitle = null;

	/**
	 * @var string|null $metaDesc - Meta description of the Page
	 */
	private static ?string $metaDesc = null;

	/**
	 * @var string|null $metaKeywords - Meta Keywords of the Page
	 */
	private static ?string $metaKeywords = null;

	/**
	 * @var string|null $canonicalLink - Canonical Link of the Page
	 */
	private static ?string $canonicalLink = null;

	/**
	 * @var string $viewFile - View-File of the Page
	 */
	private static string $viewFile;

	/**
	 * @var bool $cacheAble
	 */
	private static bool $cacheAble = false;

	/**
	 * @var int $cacheTimeSec
	 */
	private static int $cacheTimeSec = 86400;

	/**
	 * @var null|AjaxResponse
	 */
	private static ?AjaxResponse $ajaxResponse = null;

	/**
	 * @var array[] $cssFilesArray - CSS-Files of the Page
	 */
	private static array $cssFilesArray = [];

	/**
	 * @var array[] $jsFilesArray - JavaScript-Files of the Page
	 */
	private static array $jsFilesArray = [];

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
	public static function getBaseUrl(): ?string {
		if(self::$baseUrl === null) {
			throw new Exception('Page-Base URL is not set in the ' . __CLASS__ . ' Class!');
		}

		return self::$baseUrl;
	}

	/**
	 * @param string $baseUrl
	 */
	public static function setBaseUrl(string $baseUrl): void {
		self::$baseUrl = $baseUrl;
	}

	/**
	 * @return bool
	 */
	public static function isPrettyUrls(): bool {
		return self::$prettyUrls;
	}

	/**
	 * @param bool $prettyUrls
	 */
	public static function setPrettyUrls(bool $prettyUrls): void {
		self::$prettyUrls = $prettyUrls;
	}

	/**
	 * @return string
	 */
	public static function getName(): string {
		return self::$name;
	}

	/**
	 * @param string $name
	 */
	public static function setName(string $name): void {
		self::$name = $name;
	}

	/**
	 * @return string
	 */
	public static function getPage(): string {
		return self::$page;
	}

	/**
	 * @param string $page
	 */
	public static function setPage(string $page): void {
		self::$page = $page;
	}

	/**
	 * @return string
	 */
	public static function getTitle(): string {
		return self::$title;
	}

	/**
	 * @param string $title
	 */
	public static function setTitle(string $title): void {
		self::$title = $title;
	}

	/**
	 * @return null|string
	 */
	public static function getWebsiteTitle(): ?string {
		return self::$websiteTitle;
	}

	/**
	 * @param string|null $websiteTitle
	 */
	public static function setWebsiteTitle(?string $websiteTitle): void {
		self::$websiteTitle = $websiteTitle;
	}

	/**
	 * @return null|string
	 */
	public static function getMetaTitle(): ?string {
		return self::$metaTitle;
	}

	/**
	 * @param string|null $metaTitle
	 */
	public static function setMetaTitle(?string $metaTitle): void {
		self::$metaTitle = $metaTitle;
	}

	/**
	 * @return null|string
	 */
	public static function getMetaDesc(): ?string {
		return self::$metaDesc;
	}

	/**
	 * @param string|null $metaDesc
	 */
	public static function setMetaDesc(?string $metaDesc): void {
		self::$metaDesc = $metaDesc;
	}

	/**
	 * @return null|string
	 */
	public static function getMetaKeywords(): ?string {
		return self::$metaKeywords;
	}

	/**
	 * @param string|null $metaKeywords
	 */
	public static function setMetaKeywords(?string $metaKeywords): void {
		self::$metaKeywords = $metaKeywords;
	}

	/**
	 * @return null|string
	 */
	public static function getCanonicalLink(): ?string {
		return self::$canonicalLink;
	}

	/**
	 * @param string|null $canonicalLink
	 */
	public static function setCanonicalLink(?string $canonicalLink): void {
		self::$canonicalLink = $canonicalLink;
	}

	/**
	 * @return string
	 */
	public static function getViewFile(): string {
		return self::$viewFile;
	}

	/**
	 * @param string $viewFile
	 */
	public static function setViewFile(string $viewFile): void {
		self::$viewFile = $viewFile;
	}

	/**
	 * @return bool
	 */
	public static function isCacheAble(): bool {
		return self::$cacheAble;
	}

	/**
	 * @param bool $cacheAble
	 */
	public static function setCacheAble(bool $cacheAble): void {
		self::$cacheAble = $cacheAble;
	}

	/**
	 * @return int
	 */
	public static function getCacheTimeSec(): int {
		return self::$cacheTimeSec;
	}

	/**
	 * @param int $cacheTimeSec
	 */
	public static function setCacheTimeSec(int $cacheTimeSec): void {
		self::$cacheTimeSec = $cacheTimeSec;
	}

	/**
	 * @return array[]
	 */
	private static function getCssFilesArray(): array {
		return self::$cssFilesArray;
	}

	/**
	 * @return array[]
	 */
	private static function getJsFilesArray(): array {
		return self::$jsFilesArray;
	}

	/**
	 * @return AjaxResponse|null
	 */
	public static function getAjaxResponse(): ?AjaxResponse {
		return self::$ajaxResponse;
	}

	/**
	 * @param AjaxResponse|null $ajaxResponse
	 */
	public static function setAjaxResponse(?AjaxResponse $ajaxResponse): void {
		self::$ajaxResponse = $ajaxResponse;
	}

	/**
	 * Prints the Canonical-Link within HTML
	 */
	public static function printHtmlCanonicalLink(): void {
		if(! self::getCanonicalLink()) {
			return;
		}

		echo '		<link rel="canonical" href="' . self::getCanonicalLink() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Meta-Title within HTML
	 */
	public static function printHtmlMetaTitle(): void {
		if(! self::getMetaTitle()) {
			return;
		}

		echo '		<meta name="title" content="' . self::getMetaTitle() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Meta-Description within HTML
	 */
	public static function printHtmlMetaDescription(): void {
		if(! self::getMetaDesc()) {
			return;
		}

		echo '		<meta name="description"  content="' . self::getMetaDesc() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Meta-Keywords within HTML
	 */
	public static function printHtmlMetaKeywords(): void {
		if(! self::getMetaKeywords()) {
			return;
		}

		echo '		<meta name="keywords"  content="' . self::getMetaKeywords() . '" />' . PHP_EOL;
	}

	/**
	 * Prints the Page-Title plus the Website-Title if set
	 */
	public static function printWebsiteTitle(): void {
		echo  self::getTitle() . ((self::getWebsiteTitle()) ? ' - ' . self::getWebsiteTitle() : '');
	}

	/**
	 * Adds a CSS-File to the current Page
	 *
	 * @param string $file - File to add
	 * @param bool $addInHead - Add File in Head false add it before </body>
	 */
	public static function addCssFile(string $file, bool $addInHead = true): void {
		self::$cssFilesArray[] = [$file, $addInHead];
	}

	/**
	 * Adds a JS-File to the current Page
	 *
	 * @param string $file - File to add
	 * @param bool $addInHead - Add File in Head false add it before </body>
	 * @param bool $async - Loads the JS-File Async
	 */
	public static function addJSFile(string $file, bool $addInHead = true, bool $async = false): void {
		self::$jsFilesArray[] = [$file, $addInHead, $async];
	}

	/**
	 * Print all CSS-Files as HTML-Include-String
	 *
	 * @param bool $headFiles - Print Head-Files
	 */
	public static function printCssFiles(bool $headFiles): void {
		foreach(self::getCssFilesArray() as $cssFile) {
			if($cssFile[1] === $headFiles) {
				echo '		<link rel="stylesheet" href="' . $cssFile[0] . '" />' . PHP_EOL;
			}
		}
	}

	/**
	 * Print all JS-Files as HTML-Include-String
	 *
	 * @param bool $headFiles - Print Head-Files
	 */
	public static function printJsFiles(bool $headFiles): void {
		foreach(self::getJsFilesArray() as $jsFile) {
			if($jsFile[1] === $headFiles) {
				echo '		<script ' . (($jsFile[2]) ? 'async ' : '') . 'src="' . $jsFile[0] . '"></script>' . PHP_EOL;
			}
		}
	}

	/**
	 * Check if the Nav-Page is the current page
	 *
	 * @param string $navPage - Nav-Page to check
	 */
	public static function navCssCheckPage(string $navPage): void {
		if(mb_strtolower($navPage) === mb_strtolower(self::getName())) {
			echo ' active';
		}
	}

	/**
	 * Prints the Page-URL of the requested Page
	 *
	 * @param string $page - Page to get URL from
	 * @param bool $print - Print direct
	 * @return string - Page-URL
	 */
	public static function getFullPageUrl(string $page, bool $print = true): ?string {
		$pageUrl = self::getBaseUrl();

		if($page) {
			if(self::isPrettyUrls()) {
				$pageUrl .= $page . '/';
			} else {
				$pageUrl .= 'index.php?page=' . $page;
			}
		}

		if($print) {
			echo $pageUrl;
		}

		return $pageUrl;
	}

	/**
	 * Prints out the Year(s) of the Page, since it was launched
	 *
	 * @param int $startYear - Start Year of the Page
	 */
	public static function printPageSince(int $startYear = 2022): void {
		$currentYear = date('Y');

		if($startYear < $currentYear) {
			echo $startYear . ' - ' . $currentYear;
		} else {
			echo $startYear;
		}
	}

	/**
	 * Prints the AJAX-URL for the Action
	 *
	 * @param string $action - Action for the AJAX-Request
	 * @throws Exception
	 */
	public static function printAjaxURL(string $action): void {
		$ajaxUrl = self::getBaseUrl();

		if(self::isPrettyUrls()) {
			$ajaxUrl .= 'ajax/' . urlencode($action) . '/';
		} else {
			$ajaxUrl .= urlencode('index.php?page=ajax&action=' . $action);
		}

		echo '\'' . $ajaxUrl . '\'';
	}
}
