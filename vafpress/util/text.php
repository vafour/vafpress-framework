<?php

class VP_Util_Text
{

	public static function parse_md($text)
	{
		if(!function_exists('Markdown'))
		{
			require VP_DIR . '\includes\markdown\parser.php';
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

	public static function _e($string)
	{
		if(defined(VP_TEXTDOMAIN))
			echo __($string, VP_TEXTDOMAIN);
		echo $string;
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

	public static function field_type_from_class($class_name)
	{
		$prefix = 'VP_Option_Field_';
		return strtolower(str_replace($prefix, '', $class_name));
	}

}