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
	abstract public function getHTMLLang();
	abstract public function getLanguageName();
	abstract public function getPageTitle();
	abstract public function getEmptySelect();
	abstract public function getPlusName();
	abstract public function getMinusName();
	abstract public function getMultiplyWithName();
	abstract public function get0Name();
	abstract public function get1Name();
	abstract public function get2Name();
	abstract public function get3Name();
	abstract public function get4Name();
	abstract public function get5Name();
	abstract public function get6Name();
	abstract public function get7Name();
	abstract public function get8Name();
	abstract public function get9Name();
	abstract public function getAntiSpamMsgText();
	abstract public function getInvalidAjaxModusText();
	abstract public function getAntiSpamDisabledText();
	abstract public function getAntiSpamMissingFormIdText();
}
