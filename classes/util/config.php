<?php

/**
 * Config files loader
 */

class VP_Util_Config
{

	private static $_instance;

	private $_configs;

	private function __construct()
	{
		$this->_configs = array();
	}

	public static function instance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function load($config_name, $key = '')
	{
		// get the config, try to get in memory cache
		if (array_key_exists($config_name, $this->_configs))
		{
			$config = $this->_configs[$config_name];
		}
		else
		{
			if(is_file(VP_CONFIG_DIR . '/'. $config_name . '.php'))
			{
				$config     = require VP_CONFIG_DIR . '/'. $config_name . '.php';
			}
			else
			{
				throw new Exception("$config_name file not found.\n", 1);
			}
			// cache 'em
			$this->_configs[$config_name] = $config;
		}

		// if key supplied, get the specific index of config array
		$temp = $config;
		if($key !== '')
		{
			$keys = explode('.', $key);
			foreach ($keys as $key)
			{
				$temp = $temp[$key];
			}
		}
		return $temp;
	}

}