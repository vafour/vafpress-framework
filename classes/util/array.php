<?php

class VP_Util_Array
{

	public static function first($array)
	{
		if( !empty($array) and !is_null($array) )
		{
			return reset($array);
		}
		return null;
	}

	public static function deep_values($array, $the_key)
	{
		$result = array();
		foreach ($array as $key => $value)
		{
			if (is_object($value))
			{
				$result[] = $value->$the_key;
			}
			elseif (is_array($value))
			{
				$result[] = $value[$the_key];
			}
			else
			{
				$result[] = $value;
			}
		}
		return $result;
	}

	/**
	 * Combine array with the same $left to single array item
	 * from
	 * array( [0] => array( "name" => "a", "value" => "1" ), 
	 * 		  [1] => array( "name" => "a", "value" => "2" ),
	 * 		  [0] => array( "name" => "b", "value" => "3" ))
	 * to
	 * array( "a" => array( "1", "2" ), 
	 * 		  "b" => 3)
	 * @param  Array $array Array to unite
	 * @param  Mixed $left  Left side array key
	 * @param  Mixed $right Right side array key
	 * @return Array        United Array
	 */
	public static function unite($array, $left, $right)
	{
		$result = array();
		if(is_array($array))
		{
			foreach ($array as $item)
			{
				if(isset($result[$item[$left]]))
				{
					if(is_array($result[$item[$left]]))
						$result[$item[$left]][] = $item[$right];
					else
						$result[$item[$left]]   = array($result[$item[$left]], $item[$right]);
				}
				else
				{
					$result[$item[$left]] = $item[$right];
				}
			}
		}
		return $result;
	}

	public static function array_merge_recursive_all($paArray1, $paArray2)
	{
		if (!is_array($paArray1) or !is_array($paArray2)) { return $paArray2; }
		foreach ($paArray2 AS $sKey2 => $sValue2)
		{
			$paArray1[$sKey2] = self::array_merge_recursive_all(@$paArray1[$sKey2], $sValue2);
		}
		return $paArray1;
	}

	public static function array_replace_recursive($array, $array1)
	{
		if (!function_exists('array_replace_recursive'))
		{
			if(!function_exists('recurse'))
			{
				function recurse($array, $array1)
				{
					foreach ($array1 as $key => $value)
					{
						// create new key in $array, if it is empty or not an array
						if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key])))
						{
							$array[$key] = array();
						}
						// overwrite the value in the base array
						if (is_array($value))
						{
							$value = recurse($array[$key], $value);
						}
						$array[$key] = $value;
					}
					return $array;
				}
			}

			// handle the arguments, merge one by one
			$args  = func_get_args();
			$array = $args[0];
			if (!is_array($array))
			{
				return $array;
			}
			for ($i = 1; $i < count($args); $i++)
			{
				if (is_array($args[$i]))
				{
					$array = recurse($array, $args[$i]);
				}
			}
			return $array;
		}
		else
		{
			return array_replace_recursive($array, $array1);
		}
	}


}

/**
 * EOF
 */