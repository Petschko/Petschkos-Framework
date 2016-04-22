<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 12:58
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for easily creating Input Buttons
 */

/**
 * Class InputButton
 */
class InputButton extends InputField {
	/**
	 * InputButton constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param string $type - Input type of the Field
	 * @param string $value - Value of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param null|string $otherHTMLAttr - Other HTML-Attributes
	 * @param bool $required - Is the Field required
	 * @param string $dataType - Allowed Data-Type of the Field
	 */
	// @Overwrite
	public function __construct($name, $type = 'button', $value = 'Submit', $disabled = false, $otherHTMLAttr = null, $required = false, $dataType = self::TYPE_STRING) {
		parent::__construct($name, $type, null, $required, $dataType, $disabled, $otherHTMLAttr, $value);
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Button as HTML Output
	 */
	// @Overwrite | @Implement
	public function output($show = true) {
		$code = '<input type="' . $this->getType() . '" ' . $this->baseHTMLAttr() . ' value="' . $this->getEscapedValue() . '"';

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
