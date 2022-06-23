<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 02.05.2016
 * Time: 22:10
 *
 * Notes: Contains all base methods for languages
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class LangBase
 */
abstract class LangBase {
	abstract public function getHTMLLang(): string;
	abstract public function getLanguageName(): string;
	abstract public function getPageTitle(): string;
	abstract public function getEmptySelect(): string;
	abstract public function getPlusName(): string;
	abstract public function getMinusName(): string;
	abstract public function getMultiplyWithName(): string;
	abstract public function get0Name(): string;
	abstract public function get1Name(): string;
	abstract public function get2Name(): string;
	abstract public function get3Name(): string;
	abstract public function get4Name(): string;
	abstract public function get5Name(): string;
	abstract public function get6Name(): string;
	abstract public function get7Name(): string;
	abstract public function get8Name(): string;
	abstract public function get9Name(): string;
	abstract public function getAntiSpamMsgText(): string;
	abstract public function getInvalidAjaxModusText(): string;
	abstract public function getAntiSpamDisabledText(): string;
	abstract public function getAntiSpamMissingFormIdText(): string;
}
