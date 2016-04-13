<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 12.04.2016
 * Time: 11:37
 * Update: -
 * Version: 0.0.1
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

if(class_exists('cookie')) {
	if(cookie::getIsAllowed())
		$_COOKIE = escapeData($_COOKIE);
	else {
		// Check Master-Cookie every time
		if(! isset($_COOKIE[cookie::getMasterCookieName()]))
			$_COOKIE[cookie::getMasterCookieName()] = '';
		$_COOKIE[cookie::getMasterCookieName()] = escapeData($_COOKIE[cookie::getMasterCookieName()]);
	}
} else
	$_COOKIE = escapeData($_COOKIE);

