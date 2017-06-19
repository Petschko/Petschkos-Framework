<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 19.06.2017
 * Time: 16:05
 *
 * Notes: -
 */

defined('BASE_DIR') or die('Invalid File-Access');

/**
 * Class AjaxResponse
 */
class AjaxResponse {
	/**
	 * @var bool $success
	 */
	public $success;

	/**
	 * @var string $message
	 */
	public $message;

	/**
	 * @var null|string $extraInfo
	 */
	public $extraInfo = null;

	/**
	 * AjaxResponse constructor.
	 *
	 * @param string $message
	 * @param bool $success
	 */
	public function __construct($message = '', $success = false) {
		$this->message = $message;
		$this->success = $success;
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->success);
		unset($this->message);
		unset($this->extraInfo);
	}

	/**
	 * @return bool
	 */
	public function isSuccess() {
		return $this->success;
	}

	/**
	 * @param bool $success
	 */
	public function setSuccess($success) {
		$this->success = $success;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @return null|string
	 */
	public function getExtraInfo() {
		return $this->extraInfo;
	}

	/**
	 * @param null|string $extraInfo
	 */
	public function setExtraInfo($extraInfo) {
		$this->extraInfo = $extraInfo;
	}

	/**
	 * Adds a message to the current message string
	 *
	 * @param string $message
	 */
	public function addMessage($message) {
		if(mb_strlen($this->message) < 1)
			$this->setMessage($message);
		else
			$this->message .= '<br />' . $message;
	}

	/**
	 * Encode this Object to JSON and Print it
	 */
	public function printThisJson() {
		echo json_encode($this);
	}
}

