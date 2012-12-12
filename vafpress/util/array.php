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
			if(isset($result[$item['name']]))
			{
				if(is_array($result[$item['name']]))
					$result[$item['name']][] = $item['value'];
				else
					$result[$item['name']]   = array($result[$item['name']], $item['value']);
			}
			else
			{
				$result[$item['name']] = $item['value'];
			}
		}
		return $result;
	}

}

/**
 * EOF
 */