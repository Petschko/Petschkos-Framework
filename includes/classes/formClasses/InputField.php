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

/**
 * Class InputField
 */
class InputField extends FormField {

	// @Overwrite
	public function __construct($name, $type, $required = true, $dataType = self::TYPE_STRING, $disabled = false, $otherHTMLAttr = null, $value = null) {
		parent::__construct($name, $type, $required, $dataType, $disabled, $otherHTMLAttr, $value);

	}

	/**
	 * Output the Element as HTML
	 *
	 * @return string - Form Field as HTML Output
	 */
	// @Overwrite
	public function output() {
		// TODO: Implement output() method.
	}
}
