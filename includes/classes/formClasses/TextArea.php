<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 21.04.2016
 * Time: 14:39
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
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
	// @Overwrite | @Implement
	public function output($show = true) {
		$code = '<textarea ' . $this->baseHTMLAttr();

		// Add Cols && Rows
		if($this->getCols() !== null)
			$code .= ' cols="' . $this->getCols() . '"';
		if($this->getRows() !== null)
			$code .= ' rows="' . $this->getRows() . '"';

		// Close open TextArea-Tag and insert value
		$code .= '>' . $this->getValue() . '</textarea>';

		// Display HTML on show direct
		if($show)
			echo $code;

		return $code;
	}
}
