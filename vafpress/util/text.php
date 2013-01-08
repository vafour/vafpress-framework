<?php

class VP_Util_Text
{

	public static function parse_md($text)
	{
		if(!function_exists('Markdown'))
		{
			require VP_INCLUDE_DIR . '/markdown/parser.php';
		}
		return Markdown($text);
	}

	public static function make_opt($optArray)
	{
		$optString = "";
		foreach ($optArray as $key => $value)
		{
			$optString .= "(" . $key . ":" . $value . ")";
		}
		return $optString;
	}

	public static function print_if_exists($value, $format)
	{
		if (!empty($value))
		{
			if (is_array($value))
			{
				$value = implode($value, ', ');
			}
			call_user_func('printf', $format, $value);
		}	
	}

	public static function out($string, $default)
	{
		if( empty($string) )
			echo $default;
		else
			echo $string;
	}

	public static function prefix(&$item, $key, $prefix)
	{
		$item = $prefix . $item;
	}

	public static function prefix_array($array, $prefix)
	{
		array_walk( $array, 'VP_Util_Text::prefix', $prefix);
		return $array;
	}

	public static function field_type_from_class($class)
	{
		$prefix = array('VP_Control_Field_', 'VP_Option_Control_Field_');
		return strtolower(str_replace($prefix, '', $class));
	}

	public static function field_class_from_type($type)
	{
		// for now just do generic field, since impexp isn't used in parsing
		$class = 'VP_Control_Field_' . $type;
		return $class;
	}

	public static function starts_with($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}

	public static function ends_with($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0)
		{
			return true;
		}
		return (substr($haystack, -$length) === $needle);
	}

	public static function flanked_by($haystack, $left, $right = '')
	{
		if( $right == '' )
			$right = $left;
		return (self::starts_with($haystack, $left) and self::ends_with($haystack, $right));
	}

}