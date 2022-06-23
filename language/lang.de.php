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

	public function getPlusName() {
		return 'plus';
	}

	public function getMinusName() {
		return 'minus';
	}

	public function getMultiplyWithName() {
		return 'multipliziert mit';
	}

	public function get0Name() {
		return 'Null';
	}

	public function get1Name() {
		return 'Eins';
	}

	public function get2Name() {
		return 'Zwei';
	}

	public function get3Name() {
		return 'Drei';
	}

	public function get4Name() {
		return 'Vier';
	}

	public function get5Name() {
		return 'Fünf';
	}

	public function get6Name() {
		return 'Sechs';
	}

	public function get7Name() {
		return 'Sieben';
	}

	public function get8Name() {
		return 'Acht';
	}

	public function get9Name() {
		return 'Neun';
	}

	public function getAntiSpamMsgText() {
		return 'Berechne %s %s %s: (Anti-Spam)';
	}

	public function getInvalidAjaxModusText() {
		return 'Ungültiger Modus!';
	}

	public function getAntiSpamDisabledText() {
		return 'Anti-Spam ist deaktiviert!';
	}

	public function getAntiSpamMissingFormIdText() {
		return 'Es wurde keine Form-ID angegeben!';
	}
}
