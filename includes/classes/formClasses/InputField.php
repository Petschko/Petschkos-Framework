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
	 * InputField constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param string $type - Input type of the Field
	 * @param string|null $methodType - Submit method of the Form
	 * @param bool $required - Is the Field required
	 * @param bool|string $dataType - Allowed Data-Type of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param string|null $otherHTMLAttr - Other HTML-Attributes
	 * @param mixed $value - Value of the Field
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
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Input Field as HTML Output
	 */
	// @Overwrite | @Implement
	public function output($show = true) {
		$code = '<input type="' . $this->getType() . '" ' . $this->baseHTMLAttr() . ' value="' . $this->getValue() . '"';

		if($this->getSize() !== null)
			$code .= ' size="' . $this->getSize() . '"';

		if(self::isXhtml())
			$code .= ' /';
		$code .= '>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
