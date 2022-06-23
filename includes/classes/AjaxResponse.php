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
	public bool $success;

	/**
	 * @var string $message
	 */
	public string $message;

	/**
	 * @var null|string $extraInfo
	 */
	public ?string $extraInfo = null;

	/**
	 * AjaxResponse constructor.
	 *
	 * @param string $message
	 * @param bool $success
	 */
	public function __construct(string $message = '', bool $success = false) {
		$this->message = $message;
		$this->success = $success;
	}

	/**
	 * @return bool
	 */
	public function isSuccess(): bool {
		return $this->success;
	}

	/**
	 * @param bool $success
	 */
	public function setSuccess(bool $success): void {
		$this->success = $success;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage(string $message): void {
		$this->message = $message;
	}

	/**
	 * @return null|string
	 */
	public function getExtraInfo(): ?string {
		return $this->extraInfo;
	}

	/**
	 * @param string|null $extraInfo
	 */
	public function setExtraInfo(?string $extraInfo): void {
		$this->extraInfo = $extraInfo;
	}

	/**
	 * Adds a message to the current message string
	 *
	 * @param string $message
	 */
	public function addMessage(string $message): void {
		if($this->message === '') {
			$this->setMessage($message);
		} else {
			$this->message .= '<br />' . $message;
		}
	}

	/**
	 * Encode this Object to JSON and Print it
	 */
	public function printThisJson(): void {
		echo json_encode($this);
	}
}

