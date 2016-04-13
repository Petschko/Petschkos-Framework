<?php

/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 13.04.2016
 * Time: 08:32
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

/**
 * Class empty_model
 */
abstract class empty_model {
	private $exampleCol;
	// insert columns here as private

	/**
	 * empty_model constructor.
	 * @param $exampleCol
	 */
	public function __construct($exampleCol) {
		$this->exampleCol = $exampleCol;
		// Insert and set columns here
	}
}
