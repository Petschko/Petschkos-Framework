<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 26.04.2016
 * Time: 08:26
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

/**
 * Class FormError
 */
class FormError {
	/**
	 * @const int - Error-Types
	 */
	const ERROR_EMPTY_REQUIRED = 0;
	const ERROR_DATA_TYPE = 1;
	const ERROR_MIN_LEN = 2;
	const ERROR_MAX_LEN = 3;
	const ERROR_SELECT_TO_LESS = 4;
	const ERROR_SELECT_TO_MANY = 5;
	const ERROR_SELECT_NOT_IN_LIST = 6;
	const ERROR_ARRAY_IS_INVALID = 7;

	/**
	 * Contains the Error-Type
	 *
	 * @var int - Error-Type
	 */
	protected $errorType;

	/**
	 * Contains the Error-Message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Contains the affected Field
	 *
	 * @var FormField - Affected Field
	 */
	protected $field;

	/**
	 * FormError constructor.
	 *
	 * @param int $errorType - Error-Type
	 * @param FormField $field - Affected Field
	 */
	public function __construct($errorType, &$field) {
		$this->setErrorType($errorType);
		$this->setField($field);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->errorType);
		unset($this->message);
		unset($this->field);
	}

	/**
	 * Get the Error-Type
	 *
	 * @return int - Error-Type
	 */
	public function getErrorType() {
		return $this->errorType;
	}

	/**
	 * Set the Error-Type
	 *
	 * @param int $errorType - Error-Type
	 */
	public function setErrorType($errorType) {
		$this->errorType = $errorType;
	}

	/**
	 * Get the Error-Message
	 *
	 * @return string - Error-Message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Set the Error-Message
	 *
	 * @param string $message - Error-Message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * Get the affected Field
	 *
	 * @return FormField - Affected Field
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * Set the affected Field
	 *
	 * @param FormField $field - Affected Field
	 */
	public function setField(&$field) {
		$this->field =& $field;
	}
}
