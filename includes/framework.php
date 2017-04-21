<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 20:41
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Notes: Define Constances and include the main files
 */

// Define Constances
define('INCLUDE_DIR', BASE_DIR . DS . 'includes');
define('CLASS_DIR', INCLUDE_DIR . DS . 'classes');
define('CONTROLLER_DIR', INCLUDE_DIR . DS . 'controller');
define('MODEL_DIR', INCLUDE_DIR . DS . 'models');
define('VIEW_DIR', INCLUDE_DIR . DS . 'views');

// Include Base-Files
require_once(INCLUDE_DIR . DS . 'Config.php');
require_once(INCLUDE_DIR . DS . 'functions.php');
require_once(INCLUDE_DIR . DS . 'mbFunctions.php');
require_once(INCLUDE_DIR . DS . 'utf8.php');
require_once(CONTROLLER_DIR . DS . 'BaseController.php');
require_once(MODEL_DIR . DS . 'BaseDBTableModel.php');

// Include Classes
require_once(INCLUDE_DIR . DS . 'dao' . DS . 'DB.php');
require_once(INCLUDE_DIR . DS . 'dao' . DS . 'SQLError.php');

require_once(CLASS_DIR . DS . 'Cookie.php');
require_once(CLASS_DIR . DS . 'Email.php');
require_once(CLASS_DIR . DS . 'language' . DS . 'Language.php');
require_once(CLASS_DIR . DS . 'formClasses' . DS . 'Form.php');


// Setup Cookie stuff
Cookie::setIgnoreCookiePolice(Config::cookiePoliceSet);
Cookie::setCountryModeWhiteList(Config::cookiePoliceCountryModeWhiteList);

// Define-Language stuff
Language::setAvailableLanguages(Config::$enabledLanguages);
Language::setDefaultLang(Config::defaultLang);
Language::setLanguageDir(BASE_DIR . DS . 'language');
if(Cookie::getIsAllowed())
	Language::setCookiesEnabled(true);

// Don't replace anything at a FormField because we already have replaced all user vars
FormField::setXssReplace(FormField::XSS_NOT_REPLACE);

// Define Database Connection(s)
define('MAIN_DB', 'dbConnection');
new DB(
	MAIN_DB,
	Config::dbType . ':host=' . Config::dbHost . ';port=' . Config::dbPort . ';dbname=' . Config::dbWebsiteDb . ';Charset=' . Config::dbCharset,
	Config::dbUser,
	Config::dbPassword,
	array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . Config::dbCharset)
);

// Define allowed controller values and action values
$controller = array(
	// Allowed controller name => array with allowed actions
	'page' => array('home', 'error'),
);

// Define default page
$defaultController = 'page';
$defaultAction = 'home';

require_once(VIEW_DIR . DS . 'layout.php');
