<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 21:39
 * Update: -
 * Version: 0.0.1
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Class for easily creating Text-Areas
 */

/**
 * Class TextArea
 */
class TextArea extends FormField {

	/**
	 * Contains the number of cols for the TextArea
	 *
	 * @var null|int - number of cols or null for default
	 */
	private $cols = null;

	/**
	 * Contains the number of rows for the TextArea
	 *
	 * @var null|int - number of rows or null for default
	 */
	private $rows = null;

	/**
	 * TextArea constructor.
	 *
	 * @param string $name - Name of the Field
	 * @param string $type - Input type of the Field
	 * @param bool $methodType - Submit method of the Form
	 * @param bool $required - Is the Field required
	 * @param bool|string $dataType - Allowed Data-Type of the Field
	 * @param bool $disabled - Is the field disabled
	 * @param string|null $otherHTMLAttr - Other HTML-Attributes
	 * @param string|null $value - Value of the Field
	 */
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
	 * Clears Memory
	 */
	public function __destruct() {
		unset($cols);
		unset($rows);
		parent::__destruct();
	}

	/**
	 * Returns the value of the Rows for a TextArea
	 *
	 * @return null|int - Rows of the TextArea or null if none is set
	 */
	public function getRows() {
		return $this->rows;
	}

	/**
	 * Set the Rows of the TextArea
	 *
	 * @param null|int $rows - Rows of the TextArea or null to unset
	 */
	public function setRows($rows) {
		$this->rows = $rows;
	}

	/**
	 * Returns the value of the Cols for a TextArea
	 *
	 * @return null|int - Cols of the TextArea or null if none is set
	 */
	public function getCols() {
		return $this->cols;
	}

	/**
	 * Set the Cols of the TextArea
	 *
	 * @param null|int $cols - Cols of the TextArea or null to unset
	 */
	public function setCols($cols) {
		$this->cols = $cols;
	}

	/**
	 * Output the Element as HTML
	 *
	 * @param bool $show - Show HTML instant on call
	 * @return string - TextArea as HTML Output
	 */
	public function output($show = true) {
		$code = '<textarea ' . $this->baseHTMLAttr();

		// Add Cols && Rows
		if($this->getCols() !== null)
			$code .= ' cols="' . $this->getCols() . '"';
		if($this->getRows() !== null)
			$code .= ' rows="' . $this->getRows() . '"';

		// Close open TextArea-Tag and insert value
		$code .= '>' . $this->getEscapedValue() . '</textarea>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
