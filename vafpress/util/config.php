<?php

/**
 * Config files loader
 */

class VP_Util_Config
{

	private static $_instance;

	private $_path;

	private $_configs;


	private function __construct()
	{
		$this->_path    = VP_CONFIG_DIR;
		$this->_configs = array();
	}

	public static function get_instance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function load($config_name, $key = '')
	{
		if (array_key_exists($config_name, $this->_configs))
		{
			$config = $this->_configs[$config_name];
		}
		else
		{
			$config = $this->_path . '/' . $config_name . '.php';
			if(file_exists($config))
			{
				$config = require($config);
				$this->_configs[$config_name] = $config;
			}
			else
			{
				throw new Exception("$config_name file not found.\n", 1);
			}
		}
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