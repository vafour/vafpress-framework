<?php

class VP_Util_Array
{

	public static function first($array)
	{
		if( !empty($array) )
		{
			list($array) = $array;
			return $array;
		}
		return false;
	}

	public static function deep_values($array, $the_key)
	{
		$result = array();
		foreach ($array as $key => $value)
		{
			$result[] = $value[$the_key];
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
		return $result;
	}

}

/**
 * EOF
 */