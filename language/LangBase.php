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
	public abstract function getHTMLLang();
	public abstract function getLanguageName();
	public abstract function getPageTitle();
	public abstract function getEmptySelect();
}
