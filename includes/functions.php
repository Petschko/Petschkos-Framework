<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 10.05.2016
 * Time: 20:09
 * Update: -
 * Version: 0.0.1
 *
 * Notes: Useful global functions
 */

/**
 * Check if the value has the Data-Type
 *
 * @param mixed $value - Value to check
 * @param string $type - Has value this Type (string, int, float, bool etc)
 * @return bool - Value has the same type
 * @throws Exception - Invalid Type
 */
function checkDataType(&$value, $type = 'string') {
	switch(mb_strtolower($type)) {
		case 'bool':
		case 'boolean':
			if(is_string($value))
				$value = (boolean) $value;

			if(! is_bool($value))
				return false;
			break;

		case 'int':
		case 'integer':
			// Check if string contains only numbers
			if(is_string($value)) {
				if(! ctype_digit($value)) {

					// May its an negative value check it out
					if(! mb_substr($value, 0, 1) == '-') {
						if(! ctype_digit(mb_substr($value, 1)))
							return false;
					} else
						return false;
				}

				// Convert to int
				$value = (int) $value;
			}

			if(! is_int($value))
				return false;
			break;

		case 'double':
		case 'float':
			// Check if string is numeric
			if(is_string($value)) {
				if(! checkIsNumber($value))
					return false;

				// Convert to float
				$value = (float) $value;
			}

			if(! is_float($value))
				return false;
			break;

		case 'number':
			if(! checkIsNumber($value))
				return false;

			break;

		case 'text':
		case 'string':
			// Check if its a string
			if(! is_string($value))
				return false;

			break;
		default:
			throw new Exception(
				__FUNCTION__ . ': Expect a valid type to check! Your entered Type ' . mb_htmlspecialchars($type) .
				' is invalid!'
			);
	}

	return true;
}

/**
 * Detect if a value is a number
 *
 * @param mixed $value - Value to check
 * @return bool - true if its a number and false if not
 */
function checkIsNumber(&$value) {
	if(is_numeric($value))
		return true;

	if(is_string($value)) {
		// Replace comma to dot
		$value = str_replace(',', '.', $value);

		// Check if value is numeric AND if locale settings pointing to dot
		if(is_numeric(1.1)) {
			if(! is_numeric($value))
				return false;
		} else {
			// If locale point to comma replace all dots to commas
			$value = str_replace('.', ',', $value);
			if(! is_numeric($value))
				return false;
		}
	} else
		return false;

	return true;
}
