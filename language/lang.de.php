<?php
/** @noinspection AutoloadingIssuesInspection */
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
	public function getHTMLLang(): string {
		return 'de';
	}

	public function getLanguageName(): string {
		return 'Deutsch';
	}

	public function getPageTitle(): string {
		return 'Titel';
	}

	public function getEmptySelect(): string {
		return 'Auswählen...';
	}

	public function getPlusName(): string {
		return 'plus';
	}

	public function getMinusName(): string {
		return 'minus';
	}

	public function getMultiplyWithName(): string {
		return 'multipliziert mit';
	}

	public function get0Name(): string {
		return 'Null';
	}

	public function get1Name(): string {
		return 'Eins';
	}

	public function get2Name(): string {
		return 'Zwei';
	}

	public function get3Name(): string {
		return 'Drei';
	}

	public function get4Name(): string {
		return 'Vier';
	}

	public function get5Name(): string {
		return 'Fünf';
	}

	public function get6Name(): string {
		return 'Sechs';
	}

	public function get7Name(): string {
		return 'Sieben';
	}

	public function get8Name(): string {
		return 'Acht';
	}

	public function get9Name(): string {
		return 'Neun';
	}

	public function getAntiSpamMsgText(): string {
		return 'Berechne %s %s %s: (Anti-Spam)';
	}

	public function getInvalidAjaxModusText(): string {
		return 'Ungültiger Modus!';
	}

	public function getAntiSpamDisabledText(): string {
		return 'Anti-Spam ist deaktiviert!';
	}

	public function getAntiSpamMissingFormIdText(): string {
		return 'Es wurde keine Form-ID angegeben!';
	}
}
