<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 27.04.2016
 * Time: 23:17
 *
 * Notes: Contains the English language
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class LangEn
 */
class LangEn extends LangBase {
	public function getHTMLLang() {
		return 'en';
	}

	public function getLanguageName() {
		return 'English';
	}

	public function getPageTitle() {
		return 'Title';
	}

	public function getEmptySelect() {
		return 'Select...';
	}
}
