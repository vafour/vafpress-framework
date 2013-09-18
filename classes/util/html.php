<?php

class VP_Util_HTML {
	
	/**
	 * Build Classes
	 * @param  array   $params set of classes
	 * @param  boolean $format format of return string, use %s for 
	 * @return string          full classes declaration
	 */
	public static function build_class($params, $format = ' class="%s"') {
		$params = (array) $params;
		$params = array_filter($params);
		return str_replace('%s', implode(" ", $params), $format);
	}

	/**
	 * Check if the value provided matches the condition, then it deserves have a class
	 * @param  string $term      background color value
	 * @param  array  $condition condition to be matched
	 * @param  string $format    output format, use % wildcard to print the matched value
	 * @return string            class string or null
	 */
	public static function grant_class($term = '', $format = '%', $condition = true) {
		if ( (is_array($condition) and in_array($term, $condition)) or $condition === true) {
			return str_replace('%', $term, $format);
		}
		return null;
	}

	/**
	 * Build inline styles
	 * @param  array   $params set of styles declaration
	 * @param  boolean $format format of return string, use %s for 
	 * @return string          full style declaration
	 */
	public static function build_inline_style($params, $format = ' style="%s"') {
		$params = (array) $params;
		$params = array_filter($params);
		return str_replace('%s', implode(" ", $params), $format);
	}
}