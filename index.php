<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 29.12.2015 (RAW FILE)
 * Time: 19:43 (RAW FILE)
 *
 * Notes: -
 */

// Define main Constants
const BASE_DIR = __DIR__;
const DS = DIRECTORY_SEPARATOR;

// Setup error_error reporting
if(! file_exists(BASE_DIR . DS . '__DEBUG__')) {
	error_reporting(0);
} else {
	// Turn off ALL error reporting while live
	error_reporting(E_ALL);
}

// Construct the page
require_once(BASE_DIR . DS . 'includes' . DS . 'framework.php');
