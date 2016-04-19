<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 18.04.2016
 * Time: 14:43
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Notes: -
 */

/**
 * Class PageController
 */
class PageController extends BaseController {
	public function home() {
		// todo show page
	}

	public function page404() {
		require_once(VIEW_DIR . DS . 'error.php');
		// todo show error page
	}
}
