<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 29.12.2015 (RAW FILE)
 * Time: 19:43 (RAW FILE)
 *
 * Notes: -
 */

// Define main Constance's
define('BASE_DIR', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

// Setup error_error reporting
if(! file_exists(BASE_DIR . DS . "__DEBUG__"))
	error_reporting(0); // Turn off ALL error reporting while live
else
	error_reporting(E_ALL);

// Construct the page
require_once(BASE_DIR . DS . 'includes' . DS . 'framework.php');
