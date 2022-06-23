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

$response = new AjaxResponse();
$action = '';

if(isset($_GET['action']))
	$action = $_GET['action'];

switch(mb_strtolower($action)) {
	// add stuff here
	case 'cookie-accept':
		$response = cookieAccepting();
		break;
	case 'get-new-math':
		$response = getNewMathQuestion();
		break;
	default:
		$response->addMessage('Ungültiger Modus!');
		$response->setSuccess(false);
}

// Handle not Real-AJAX Requests
if(isset($_POST['origin'])) {
	$origin = urlencode($_POST['origin']);

	Page::setPage($origin);
	Page::setAjaxResponse($response);
	unset($_GET['action']);
} else
	$response->printThisJson();

/**
 * Set the Cookie which saves if the user accept/deny Cookies
 *
 * @return null
 */
function cookieAccepting() {
	$accept = false;
	if(isset($_POST['cookie-policy']))
		$accept = $_POST['cookie-policy'];

	$accept = $accept === 'accept';

	Cookie::enableCookies($accept);

	return null;
}

/**
 * Gets a new Math question
 *
 * @return AjaxResponse
 */
function getNewMathQuestion() {
	$response = new AjaxResponse();
	if(! isset($_POST['formId']))
		$_POST['formId'] = null;

	if(Config::enableAntiSpam) {
		if(empty($_POST['formId'])) {
			$response->setMessage('Es wurde keine Form-ID angegeben!');

			return $response;
		}

		$antiSpam = AntiSpam::getInstance($_POST['formId']);
		$math = $antiSpam->getInnerMathLabelContent();

		$response->setSuccess((bool) $math);
		$response->setMessage(1);
		$response->setExtraInfo($math);

		return $response;
	}

	$response->setMessage('Anti-Spam ist deaktiviert!');

	return $response;
}
