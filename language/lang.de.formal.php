<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 02.05.2016
 * Time: 23:14
 *
 * Notes: Contains German Language with "Sie" instead of "Du"
 */

defined('BASE_DIR') or die('Invalid File-Access');

// Req Parent File
require_once(LANG_DIR . DS . 'lang.de.php');

/**
 * Class LangDeFormal
 */
class LangDeFormal extends LangDe {
	public function getLanguageName() {
		return parent::getLanguageName() . ' (Sie)';
	}

	public function getAntiSpamMsgText() {
		return 'Berechnen Sie %s %s %s: (Anti-Spam)';
	}
}
