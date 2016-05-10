<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 22.04.2016
 * Time: 00:13
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
	 * Contains if the Name is shown on output
	 *
	 * @var bool - Display the name on output
	 */
	protected $showName = false;

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
	public function __construct($name, $type = 'button', $value = 'Submit', $disabled = false, $otherHTMLAttr = null, $required = false, $dataType = self::TYPE_STRING) {
		parent::__construct($name, $type, null, $required, $dataType, $disabled, $otherHTMLAttr, $value);
	}

	/**
	 * Clears Memory
	 */
	public function __destruct() {
		unset($this->showName);
		parent::__destruct();
	}

	/**
	 * Shows if the name is shown on output
	 *
	 * @return boolean - Display the Name on output
	 */
	protected function isShowName() {
		return $this->showName;
	}

	/**
	 * Set if the Name is shown on output. Useful when using GET to not display the Name
	 *
	 * @param boolean $showName - Show the Name
	 */
	public function setShowName($showName) {
		$this->showName = $showName;
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - Button as HTML Output
	 */
	public function output($show = true) {
		$htmlBase = $this->baseHTMLAttr();

		// Remove name if not wanted
		if(! $this->isShowName())
			$htmlBase = str_replace('name="' . $this->getName() . '"', '', $htmlBase);

		$code = '<input type="' . $this->getType() . '" ' . $htmlBase . ' value="' . $this->getEscapedValue() . '"';

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
