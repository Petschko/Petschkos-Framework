<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 20.04.2016
 * Time: 11:11
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for easily creating Input field
 */

// Include sub-classes
require_once('FileField.php');
require_once('InputButton.php');
require_once('RadioCheckButton.php');

/**
 * Class InputField
 */
class InputField extends FormField {
	/**
	 * Contains if the input field supports auto complete
	 *
	 * @var bool - Is auto complete
	 */
	private $autoComplete = true;

	/**
	 * InputField constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param string $type - Input type of the Field
	 * @param string|null $methodType - Submit method of the Form
	 * @param bool $required - Is the Field required
	 * @param bool|string $dataType - Allowed Data-Type of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param string|null $otherHTMLAttr - Other HTML-Attributes
	 * @param string|null $value - Value of the Field
	 */
	// @Overwrite
	public function __construct($name, $type, $methodType, $required = true, $dataType = self::TYPE_STRING, $disabled = false, $otherHTMLAttr = null, $value = null) {
		parent::__construct($name, $type, $required, $dataType, $disabled, $otherHTMLAttr, $value);

		// Get the user value
		if($methodType !== null) {
			$userValue = $this->getCurrentValue($methodType);
			if($userValue !== null)
				$this->setValue($userValue);
		}
	}

	/**
	 * Return if auto complete is allowed
	 *
	 * @return boolean - Is auto complete allowed
	 */
	public function isAutoComplete() {
		return $this->autoComplete;
	}

	/**
	 * Set if auto complete is allowed
	 *
	 * @param boolean $autoComplete - Is auto complete allowed
	 */
	public function setAutoComplete($autoComplete) {
		$this->autoComplete = $autoComplete;
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Input Field as HTML Output
	 */
	// @Overwrite | @Implement
	public function output($show = true) {
		$code = '<input type="' . $this->getType() . '" ' . $this->baseHTMLAttr() . ' value="' . $this->getEscapedValue() . '"';

		if($this->getSize() !== null)
			$code .= ' size="' . $this->getSize() . '"';

		// Setup autocomplete
		if(self::isHtml5()) {
			if(! $this->isAutoComplete() && $this->getType() == 'password')
				trigger_error('Use-Violation: autocomplete="off" is not allowed on a password field!', E_USER_NOTICE);
			else if(! $this->isAutoComplete())
				$code .= ' autocomplete="off"';
		}

		if(self::isXhtml())
			$code .= ' /';
		$code .= '>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
