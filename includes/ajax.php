<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 19.06.2017
 * Time: 18:50
 *
 * Notes: -
 */

defined('BASE_DIR') or die('Invalid File-Access');

require_once(CLASS_DIR . DS . 'AjaxResponse.php');

$action = '';

if(isset($_GET['action']))
	$action = $_GET['action'];

switch(mb_strtolower($action)) {
	// add stuff here
	default:
		// VOID
}
