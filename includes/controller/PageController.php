<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 18.04.2016
 * Time: 21:43
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
		echo 1;
		// todo show page
	}

	public function page404() {
		require_once(VIEW_DIR . DS . 'error.php');
		// todo show error page
	}
}
