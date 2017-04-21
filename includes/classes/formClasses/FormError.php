<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 26.04.2016
 * Time: 20:26
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for Form-Error Messages
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
	private function getErrorType() {
		return $this->errorType;
	}

	/**
	 * Set the Error-Type
	 *
	 * @param int $errorType - Error-Type
	 */
	private function setErrorType($errorType) {
		$this->errorType = $errorType;
	}

	/**
	 * Get the Error-Message
	 *
	 * @return string - Error-Message
	 */
	public function getMessage() {
		if($this->message === null)
			$this->setMessage($this->generateErrorMsg());

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

	/**
	 * Generates the Error-Message
	 *
	 * @return null|string - Error-Message or null
	 */
	private function generateErrorMsg() {
		$field = $this->getField();

		switch($this->getErrorType()) {
			case self::ERROR_EMPTY_REQUIRED:
				return Language::get()->getFormErrorEmptyReq($field->getName());

			case self::ERROR_MIN_LEN:
				return Language::get()->getFormErrorMin($field->getName(), $field->getMinLen());

			case self::ERROR_MAX_LEN:
				return Language::get()->getFormErrorMax($field->getName(), $field->getMaxLen());

			case self::ERROR_DATA_TYPE:
			case self::ERROR_ARRAY_IS_INVALID:
				return Language::get()->getFormErrorDataT($field->getName());

			case self::ERROR_SELECT_TO_LESS:
				/**
				 * @var SelectList $field - Select-List
				 */
				return Language::get()->getFormErrorSelectLess($field->getName(), $field->getMinSelectCount());

			case self::ERROR_SELECT_TO_MANY:
				/**
				 * @var SelectList $field - Select-List
				 */
				return Language::get()->getFormErrorSelectMuch($field->getName(), $field->getSize());

			case self::ERROR_SELECT_NOT_IN_LIST:
				/**
				 * @var SelectList $field - Select-List
				 */
				return Language::get()->getFormErrorSelectNotInList($field->getName(), $field->getEscapedValue());

			default:
				return '';
		}
	}
}
