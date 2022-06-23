<?php
/** @noinspection AutoloadingIssuesInspection */
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
	public function getHTMLLang(): string {
		return 'en';
	}

	public function getLanguageName(): string {
		return 'English';
	}

	public function getPageTitle(): string {
		return 'Title';
	}

	public function getEmptySelect(): string {
		return 'Select...';
	}

	public function getPlusName(): string {
		return 'plus';
	}

	public function getMinusName(): string {
		return 'minus';
	}

	public function getMultiplyWithName(): string {
		return 'multiply with';
	}

	public function get0Name(): string {
		return 'null';
	}

	public function get1Name(): string {
		return 'one';
	}

	public function get2Name(): string {
		return 'two';
	}

	public function get3Name(): string {
		return 'three';
	}

	public function get4Name(): string {
		return 'four';
	}

	public function get5Name(): string {
		return 'five';
	}

	public function get6Name(): string {
		return 'six';
	}

	public function get7Name(): string {
		return 'seven';
	}

	public function get8Name(): string {
		return 'eight';
	}

	public function get9Name(): string {
		return 'nine';
	}

	public function getAntiSpamMsgText(): string {
		return 'Calculate %s %s %s: (Anti-Spam)';
	}

	public function getInvalidAjaxModusText(): string {
		return 'Invalid Mode!';
	}

	public function getAntiSpamDisabledText(): string {
		return 'Anti-Spam is disabled!';
	}

	public function getAntiSpamMissingFormIdText(): string {
		return 'You didn\'t set a Form-ID!';
	}
}
