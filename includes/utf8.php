<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 12.04.2016
 * Time: 22:29
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Notes: Procedural Code that managed all of the UTF-8 Stuff and data escape
 */

// Include MultiByte functions
require_once('mbFunctions.php');

// Check if multi byte functions are installed
checkMultiByteFunctions();

// Set Header/Encoding
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Escape and Convert Data
$_GET = escapeData($_GET);
$_POST = escapeData($_POST);
$_SERVER = escapeData($_SERVER);
$_REQUEST = escapeData($_REQUEST);
$_FILES = escapeData($_FILES);

if(class_exists('Cookie')) {
	if(Cookie::getIsAllowed())
		$_COOKIE = escapeData($_COOKIE);
	else {
		// Check Master-Cookie every time
		if(! isset($_COOKIE[Cookie::getMasterCookieName()]))
			$_COOKIE[Cookie::getMasterCookieName()] = '';

		$_COOKIE[Cookie::getMasterCookieName()] = escapeData($_COOKIE[Cookie::getMasterCookieName()]);
	}
} else
	$_COOKIE = escapeData($_COOKIE);

