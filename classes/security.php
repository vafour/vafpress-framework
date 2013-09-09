<?php

class VP_Security
{

	/**
	 * Singleton instance of the class
	 * @var VP_Security
	 */
	private static $_instance;

	private $_whitelist = array();

	public static function instance()
	{
		if (is_null(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;
	}

	public function whitelist_function($name)
	{
		if( ! in_array($name, $this->_whitelist) )
		{
			$this->_whitelist[] = $name;
			return $name;
		}
		return false;
	}

	public function is_function_whitelisted($name)
	{
		if( in_array($name, $this->_whitelist) )
			return true;
		return false;
	}

}

/**
 * EOF
 */