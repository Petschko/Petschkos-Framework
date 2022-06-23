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

	public function getPlusName() {
		return 'plus';
	}

	public function getMinusName() {
		return 'minus';
	}

	public function getMultiplyWithName() {
		return 'multiply with';
	}

	public function get0Name() {
		return 'null';
	}

	public function get1Name() {
		return 'one';
	}

	public function get2Name() {
		return 'two';
	}

	public function get3Name() {
		return 'three';
	}

	public function get4Name() {
		return 'four';
	}

	public function get5Name() {
		return 'five';
	}

	public function get6Name() {
		return 'six';
	}

	public function get7Name() {
		return 'seven';
	}

	public function get8Name() {
		return 'eight';
	}

	public function get9Name() {
		return 'nine';
	}

	public function getAntiSpamMsgText() {
		return 'Calculate %s %s %s: (Anti-Spam)';
	}

	public function getInvalidAjaxModusText() {
		return 'Invalid Mode!';
	}

	public function getAntiSpamDisabledText() {
		return 'Anti-Spam is disabled!';
	}

	public function getAntiSpamMissingFormIdText() {
		return 'You didn\'t set a Form-ID!';
	}
}
