<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 20:41
 *
 * Notes: Define Constants and include the main files
 */

defined('BASE_DIR') or die('Invalid File-Access');

// Get config
require_once(BASE_DIR . DS . 'includes' . DS . 'Config.php');

// Define Constants
define('CACHE_DIR', BASE_DIR . DS . 'cache');
define('INCLUDE_DIR', BASE_DIR . DS . 'includes');
define('CLASS_DIR', INCLUDE_DIR . DS . 'classes');
define('SQL_MODEL_DIR', INCLUDE_DIR . DS . 'dao' . DS . 'models');
define('TEMPLATE_DIR', BASE_DIR . DS . 'template');
define('EMAIL_DIR', TEMPLATE_DIR . DS . 'email');
define('LANG_DIR', BASE_DIR . DS . 'language');
define('LANG_DIR_JS', Config::pageBaseURL . 'js/language/');

// Include Base-Files
require_once(INCLUDE_DIR . DS . 'functions.php');
require_once(INCLUDE_DIR . DS . 'utf8.php');

// Include DAO
if(Config::dbEnabled) {
	require_once(SQL_MODEL_DIR . DS . 'BaseDBTableModel.php');
	require_once(INCLUDE_DIR . DS . 'dao' . DS . 'DB.php');
	require_once(INCLUDE_DIR . DS . 'dao' . DS . 'SQLError.php');

	// Include DB-Table-Models
	// VOID
}

// Include Classes
require_once(CLASS_DIR . DS . 'Cookie.php');
require_once(CLASS_DIR . DS . 'Page.php');
require_once(CLASS_DIR . DS . 'Email.php');
require_once(CLASS_DIR . DS . 'Language.php');


// Setup Cookie stuff
Cookie::setIgnoreCookiePolice(Config::cookiePoliceSet);
Cookie::setCountryModeWhiteList(Config::cookiePoliceCountryModeWhiteList);

// Define-Language stuff
Language::setAvailableLanguages(Config::$enabledLanguages);
Language::setDefaultLang(Config::defaultLang);
Language::setLanguagePhpDir(LANG_DIR);
Language::setLanguageJsDir(LANG_DIR_JS);

if(Cookie::getIsAllowed())
	Language::setCookiesEnabled(true);

if(Config::dbEnabled) {
	// Define Database Connection(s)
	define('MAIN_DB', 'dbConnection');
	new DB(
		MAIN_DB,
		Config::dbType . ':host=' . Config::dbHost . ';port=' . Config::dbPort . ';dbname=' . Config::dbWebsiteDb . ';Charset=' . Config::dbCharset,
		Config::dbUser,
		Config::dbPassword,
		array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . Config::dbCharset)
	);
}

require_once(INCLUDE_DIR . DS . 'router.php');

if(Config::cacheEnabled && Page::isCacheAble()) {
	require_once(CLASS_DIR . DS . 'Cache.php');
	new Cache(
		Page::getName(),
		CACHE_DIR . DS,
		INCLUDE_DIR . DS . 'layout.php',
		Page::getCacheTimeSec()
	);
} else
	require_once(INCLUDE_DIR . DS . 'layout.php');

closePage();
