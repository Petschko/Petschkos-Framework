<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 16:38
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Notes: Routes to the correct Controller
 */

/**
 * Call the correct Controller with the Action
 *
 * @param string $controller
 * @param string $action
 */
function route($controller, $action) {
	// Check if there is a selected controller
	switch($controller) {
		// Insert include controller files and may models
		case 'page':
			require_once(CONTROLLER_DIR . DS . 'PageController.php');
			$calledController = new PageController();
			break;
		default:
			return;
	}

	$calledController->{$action}();
}

// Get default
if(! isset($_GET['mode']))
	$_GET['mode'] = $defaultController;
if(! isset($_GET['action']))
	$_GET['action'] = $defaultAction;

// Call the controller if exists else show an error
if(array_key_exists($_GET['mode'], $controller)) {
	if(in_array($_GET['action'], $controller[$_GET['mode']]))
		route($_GET['mode'], $_GET['action']);
	else
		route('page', 'page404');
}
else
	route('page', 'page404');
