<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 18:53
 *
 * Notes: Routes to the correct Controller
 */

defined('BASE_DIR') or die('Invalid File-Access');

$page = 'home';

if(isset($_GET['page'])) {
	if($_GET['page']) {
		$page = $_GET['page'];
	}
}

// Get Requested Page
$page = mb_strtolower($page);

// Define global Page stuff
Page::setBaseUrl(Config::WEBSITE_BASE_URL);
Page::setPrettyUrls(Config::WEBSITE_PRETTY_URLS);
Page::setWebsiteTitle(Config::WEBSITE_TITLE);
if(Config::CACHE_LIFETIME_SEC) {
	Page::setCacheTimeSec(Config::CACHE_LIFETIME_SEC);
}
Page::setPage($page);

// Add Global JS/CSS Files
Page::addCssFile(Config::WEBSITE_BASE_URL . 'styles/style.css');
Page::addJSFile(Language::getLangJsFileUri());

// Check if AJAX-Function
if($page === 'ajax') {
	Page::setCacheAble(false);
	Page::setPage('ajax');

	require_once(INCLUDE_DIR . DS . 'ajax.php');

	if(Page::getPage() === 'ajax') {
		closePage();
	} else {
		$page = Page::getPage();
	}
}

// Add CSS/JS-Files Files for every page; Also specify meta stuff title etc
switch($page) {
	case 'ajax':
		require_once(INCLUDE_DIR . DS . 'ajax.php');
		closePage();
		break;
	case 'home':
		Page::setName('Home');
		Page::setTitle('Startseite');
		//Page::setMetaDesc('');
		Page::setCanonicalLink(Page::getFullPageUrl($page, false));
		Page::setViewFile('home.php');
		break;
	case '403':
		header('HTTP/1.0 403 Forbidden');

		$page = '403';
		Page::setName('403');
		Page::setTitle('403 - Zugriff verweigert / Access denied');
		Page::setCanonicalLink(Page::getFullPageUrl($page, false));
		Page::setViewFile('403.html');
		break;
	case '404':
	default:
		header("HTTP/1.0 404 Not Found");

		$page = '404';
		Page::setName('404');
		Page::setTitle('404 - Nicht gefunden / Not found');
		Page::setCanonicalLink(Page::getFullPageUrl($page, false));
		Page::setViewFile('404.html');
}
