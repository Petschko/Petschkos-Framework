<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 24.11.2015 (RAW FILE)
 * Time: 15:00 (RAW FILE)
 * Update: 13.01.2016 (RAW FILE)
 * Version: 1.0.0 (RAW FILE)
 *
 * Notes: -
 */

// Define main Constance's
define('BASE_DIR', dirname(__FILE__));
define('OUT_BASE_DIR', '.');
define('DS', DIRECTORY_SEPARATOR);

// Setup error_error reporting
if(! file_exists(BASE_DIR . DS . "__DEBUG__"))
	error_reporting(0); // Turn off ALL error reporting while live
else
	error_reporting(E_ALL);

// Construct the page
require_once(BASE_DIR . DS . 'includes' . DS . 'framework.php');
