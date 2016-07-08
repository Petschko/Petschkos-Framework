<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 10.05.2016
 * Time: 20:09
 * Update: 08.07.2016
 * Version: 0.1.2 (More checks for E-Mail validation)
 * 0.1.1 (Fixed E-Mail and URL-Validation and fixed local-Bug)
 * 0.1.0 (Added E-Mail-Check and URL-Check function)
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

			// Convert string to correct number
			if(mb_strpos($value, '.') === false && mb_strpos($value, ',') === false)
				$value = (int) $value;
			else
				$value = (float) $value;

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

/**
 * Detects if the value is a valid E-Mail
 *
 * @param mixed $value - Value to check
 * @param bool $allowLocal - true allows local addresses like admin@localhost or admin@example
 * @return bool - true if E-Mail is valid else false
 */
function checkIsValidEmail($value, $allowLocal = false) {
	if(! is_string($value))
		return false;
	
	// Don't allow some special chars
	if(preg_match('/[^a-zA-Z\d\_\-\.\@\:]/', $value))
		return false;

	// Check E-Mail pattern
	$atPos = mb_strpos($value, '@');
	$lastPointPos = mb_strrpos($value, '.');

	// Only allow 1 @
	if(mb_substr_count($value, '@') > 1)
		return false;

	if(! $atPos || (! $lastPointPos && ! $allowLocal))
		return false;

	// Use this rule if no dot is found and local is allowed
	if($allowLocal && ! $lastPointPos) {
		if(! ($atPos > 0 && $atPos < mb_strlen($value)))
			return false;

		return true;
	}

	// Don't allow double . after the @
	if(mb_substr_count(mb_substr($value, $atPos), '..'))
		return false;

	// This is the default rule
	if(! ($atPos > 0 && $lastPointPos > ($atPos + 1) && mb_strlen($value) > $lastPointPos))
		return false;
	
	return true;
}

/**
 * Detects if the value is a valid URL
 *
 * @param mixed $value - Value to check
 * @param bool $allowLocal - true allows local addresses like http://localhost/ or http://example/
 * @return bool - true if URL is valid else false
 */
function checkIsValidUrl($value, $allowLocal = false) {
	if(version_compare(PHP_VERSION, '5.3.3') >= 0) {
		if(! filter_var($value, FILTER_VALIDATE_URL))
			return false;
	} else {
		trigger_error('You use an old Version of PHP, please consider to Update!');

		if(! preg_match('/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $value));
			return false;
	}

	// Extract Hostname
	$protocolSeparatorPos = mb_strpos($value, '://');
	if(! $protocolSeparatorPos)
		return false;

	$urlWithoutProtocol = mb_substr($value, $protocolSeparatorPos + 3);
	$hostSeparatorPos = mb_strpos($urlWithoutProtocol, '/');
	$hostname = mb_substr($urlWithoutProtocol, 0, ($hostSeparatorPos === false) ? mb_strlen($urlWithoutProtocol) : $hostSeparatorPos);

	// Check if host is correct
	if(mb_strlen($hostname) < 1)
		return false;

	$dots = mb_substr_count($hostname, '.');
	if($dots < 1 && ! $allowLocal)
		return false;
	else if($allowLocal)
		return true;

	$firstDotPos = mb_strpos($hostname, '.');
	$lastDotPos = mb_strrpos($hostname, '.');
	$portCount = mb_substr_count($hostname, ':');

	// Check if dot is not at the beginning and end
	if(! ($firstDotPos > 0 && $lastDotPos < mb_strlen($hostname)))
		return false;

	// 2 Dots in row are not allowed
	if(mb_substr_count($hostname, '..'))
		return false;

	// If hast port separator check it too
	if($portCount > 0) {
		// Port-separator is only 1 time allowed
		if($portCount > 1)
			return false;

		// Check space between last point and separator and if space after separator
		$portPos = mb_strpos($hostname, ':');

		if(! ($portPos > $lastDotPos + 1 && $portPos < mb_strlen($hostname)))
			return false;

		$port = mb_substr($hostname, $portPos + 1);

		// Port can only a int number
		if(! checkDataType($port, 'int'))
			return false;
	}

	// Check last section for errors
	$urlEnd = mb_substr($urlWithoutProtocol, $hostSeparatorPos);

	// Don't allow double slashes at the end
	if(mb_substr_count($urlEnd, '//'))
		return false;

	return true;
}
