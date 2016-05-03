<?php
/**
 * Author: Peter Dragicevic [peter-91@hotmail.de]
 * Authors-Website: http://petschko.org/
 * Date: 12.04.2016
 * Time: 21:41
 * Update: -
 * Version: 0.0.1
 * @package Petschkos Framework
 *
 * Licence: http://creativecommons.org/licenses/by-sa/4.0/
 * You are free to use this!
 *
 * Notes: Contains (edited) function to have an easier management with multi byte stuff
 */

/**
 * Like http://php.net/htmlentities (htmlentities) but with different default parameters
 *
 * @param string|array $string - The string to convert
 * @param int $quote - Quotes, see the functions documentation itself ( http://php.net/htmlentities ) - Default: ENT_QUOTES
 * @param string $charset - Character-Set-Encoding - Default: UTF-8
 * @param bool $double_encode - Convert full string or ignore already converted entities - Default: true (convert full)
 * @return string - The converted string
 */
function mb_htmlentities($string, $quote = ENT_QUOTES, $charset = 'UTF-8', $double_encode = true) {
	if(is_array($string)) {
		$tmp = array();
		foreach($string as $key => $value)
			$tmp[$key] = mb_htmlentities($value, $quote, $charset, $double_encode);

		return $tmp;
	}

	return htmlentities($string, $quote, $charset, $double_encode);
}

/**
 * Like http://php.net/htmlspecialchars (htmlspecialchars) but with different default parameters
 *
 * @param string|array $string - The string to convert
 * @param int $flags - Quotes, see the functions documentation itself ( http://php.net/htmlspecialchars )- Default: ENT_QUOTES
 * @param string $encoding - Character-Set-Encoding - Default: UTF-8
 * @param bool $double_quote - Convert full string or ignore already converted entities - Default: true (convert full)
 * @return string - The converted string
 */
function mb_htmlspecialchars($string, $flags = ENT_QUOTES, $encoding = 'UTF-8', $double_quote = true) {
	if(is_array($string)) {
		$tmp = array();
		foreach($string as $key => $value)
			$tmp[$key] = mb_htmlspecialchars($value, $flags, $encoding, $double_quote);

		return $tmp;
	}

	return htmlspecialchars($string, $flags, $encoding, $double_quote);
}

/**
 * Like http://php.net/html_entity_decode (html_entity_decode) but with different default parameters
 *
 * @param string|array $string - The string to convert
 * @param int $quote - Quotes, see the functions documentation itself ( http://php.net/html_entity_decode ) - Default: ENT_QUOTES
 * @param string $charset - Character-Set-Encoding - Default: UTF-8
 * @return string - The converted string
 */
function mb_html_entity_decode($string, $quote = ENT_QUOTES, $charset = 'UTF-8') {
	if(is_array($string)) {
		$tmp = array();
		foreach($string as $key => $value)
			$tmp[$key] = mb_html_entity_decode($value, $quote, $charset);

		return $tmp;
	}

	return html_entity_decode($string, $quote, $charset);
}

/**
 * Encode an string to RFC - Like this: http://php.net/rawurlencode
 * ----
 * Improvement: Thanks to bolvaritamas@vipmail.hu on PHP.NET for FULL UTF-8 convert!
 *
 * @param array|string $string - the string/array which should converted
 * @param null|string $encoding - Encoding for mb_functions or null for default
 * @return array|string - the converted string/array
 */
function mb_rawurlencode($string, $encoding = null) {
	if($encoding === null)
		$encoding = mb_internal_encoding();

	if(is_array($string)) {
		$tmp = array();
		foreach($string as $key => $value)
			$tmp[$key] = mb_rawurlencode($value, $encoding);

		return $tmp;
	}

	$result = "";
	$length = mb_strlen($string, $encoding);

	for($i = 0; $i < $length; $i++)
		$result .= '%' . wordwrap(bin2hex(mb_substr($string, $i, 1, $encoding)), 2, '%', true);

	return $result;
}

/**
 * Like http://php.net/urldecode
 *
 * @param string|array - $str unicode and ulrencoded string, see also: http://php.net/urldecode
 * @return string - decoded string
 */
function mb_urldecode($string) {
	if(is_array($string)) {
		$tmp = array();
		foreach($string as $key => $value)
			$tmp[$key] = mb_urldecode($value);

		return $tmp;
	}

	$string = preg_replace('/%u([0-9a-f]{3,4})/i', '&#x\\1;', urldecode($string));

	return mb_html_entity_decode($string);
}

/**
 * Same as http://php.net/basename (basename) but work also with MultiBytes File/Directory-Names
 * /!\ Usual not needed to use! There are no MultiBytes-File/Directory-Names yet, but may needed for some of you if your server has them Like Chine/Russian etc /!\
 *
 * @param string $path - Path to convert
 * @param string|null $suffix - Remove this ending if exists
 * @param null|string $encoding - Encoding for mb_functions or null for default
 * @return string -  Filename without path (and may without suffix)
 */
function mb_basename($path, $suffix = null, $encoding = null) {
	if($encoding === null)
		$encoding = mb_internal_encoding();

	if(mb_stripos($path, DIRECTORY_SEPARATOR) !== false)
		$basename = mb_substr($path, mb_strripos($path, DIRECTORY_SEPARATOR), $encoding);
	else
		$basename = $path;

	if($suffix && mb_stripos($basename, $suffix, null, $encoding)) {
		$suffix_pos = mb_strlen($suffix, $encoding) * -1;
		$base_end = mb_substr($basename, $suffix_pos, mb_strlen($basename, $encoding), $encoding);

		if($base_end == $suffix)
			$basename = mb_substr($basename, 0, $suffix_pos, $encoding);
	}

	return $basename;
}

/**
 * Like http://php.net/lcfirst (lcfirst) but compatible with MultiByte Characters
 *
 * @param string $string - String to convert, see also: http://php.net/lcfirst
 * @param null|string $encoding - Encoding for mb_functions or null for default
 * @return string - String with first char lower
 */
function mb_lcfirst($string, $encoding = null) {
	if($encoding === null)
		$encoding = mb_internal_encoding();

	return mb_strtolower(mb_substr($string, 0, 1, $encoding), $encoding) . mb_substr($string, 1, mb_strlen($string, $encoding), $encoding);
}

/**
 * Like http://php.net/ucfirst (lcfirst) but compatible with MultiByte Characters
 *
 * @param string $string - String to convert, see also: http://php.net/ucfirst
 * @param null|string $encoding - Encoding for mb_functions or null for default
 * @return string - String with first char upper
 */
function mb_ucfirst($string, $encoding = null) {
	if($encoding === null)
		$encoding = mb_internal_encoding();

	return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding) . mb_substr($string, 1, mb_strlen($string, $encoding), $encoding);
}

/**
 * Detect encoding and convert it to UTF-8 and remove not allowed Encoding
 *
 * @param string|array $string - Dirty, may not UTF-8 String
 * @return bool|mixed|string - Clean UTF-8 String or false on error
 * @throws Exception - UTF-7 Warn
 */
function removeNonUtf8($string) {
	if(is_array($string)) {
		$tmp = array();
		foreach($string as $key => $value)
			$tmp[$key] = removeNonUtf8($value);

		return $tmp;
	}

	if(mb_detect_encoding($string) != 'UTF-8')
		$string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string)); // Convert non UTF-8 String to UTF-8

	// Exit on UTF7 (To avoid a Bug on IE)
	if(mb_detect_encoding($string) == 'UTF-7')
		throw new Exception('UTF-7 is not allowed!');

	// Check if string is ascii or utf8 (utf-8 is ascii compatible, that is why this should be also checked)
	if(mb_detect_encoding($string) != 'UTF-8' && mb_detect_encoding($string) != 'ASCII')
		return false;

	$string = preg_replace('/[\xF0-\xF7].../s', '', $string); // Remove UTF16 chars

	return $string;
}

/**
 * Checks if MultiBytes (mb) functions exists - They are needed for this Class!
 *
 * @throws Exception - Warning MB-Functions not found
 */
function checkMultiByteFunctions() {
	if(! function_exists('mb_detect_encoding') || ! function_exists('mb_convert_encoding'))
		throw new Exception('[Security]: Can\'t find mb_*_encoding functions, make sure that you have them in your PHP-Install');
	if(! function_exists('mb_substr') || ! function_exists('mb_strlen') || ! function_exists('mb_strripos') || ! function_exists('mb_stripos'))
		throw new Exception('[Security]: Can\'t find mb_str* functions, make sure that you have them in your PHP-Install');
}

/**
 * Escape/Converts data
 *
 * @param string|array $subject - Unconverted/Unescaped data
 * @param bool $entities - Use htmlentities instead of htmlspecialchars
 * @return array|string - Escaped/Converted data
 * @throws Exception - Bad-Charset exception
 */
function escapeData($subject, $entities = false) {
	if(is_array($subject)) {
		$tmp = array();
		foreach($subject as $key => $value)
			$tmp[$key] = escapeData($value, $entities);

		return $tmp;
	}

	// Try to convert and remove hostile chars
	$subject = removeNonUtf8($subject);

	if($entities)
		return mb_htmlentities($subject);

	return mb_htmlspecialchars($subject);
}
