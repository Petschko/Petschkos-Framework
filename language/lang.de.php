<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 02.05.2016
 * Time: 23:14
 *
 * Notes: Contains the German language
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class LangDe
 */
class LangDe extends LangBase {
	public function getHTMLLang() {
		return 'de';
	}

	public function getLanguageName() {
		return 'Deutsch';
	}

	public function getPageTitle() {
		return 'Titel';
	}

	public function getEmptySelect() {
		return 'Auswählen...';
	}
}
