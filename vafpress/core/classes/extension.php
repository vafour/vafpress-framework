<?php

class VP_Extension
{

	private static $_extensions = array();

	private $_config_dir;

	private $_views_dir;

	private $_namespace;

	private $_bootstrap_file;

	private $_functions_file;

	public function __construct($namespace)
	{
		$this->set_namespace($namespace);
		return $this;
	}

	public static function add_extension($namespace)
	{
		self::$_extensions[$namespace] = new VP_Extension($namespace);
		return self::$_extensions[$namespace];
	}
	
	public static function get_extension($namespace)
	{
		if(array_key_exists($namespace, self::$_extensions))
		{
			return self::$_extensions[$namespace];
		}
		return null;
	}

	public static function get_extensions()
	{
		return self::$_extensions;
	}

	public function set_namespace($namespace)
	{
		$this->_namespace = $namespace;
		return $this;
	}

	public function set_config_dir($dir)
	{
		$this->_config_dir = $dir;
		return $this;
	}

	public function set_views_dir($dir)
	{
		$this->_views_dir = $dir;
		return $this;
	}

	public function set_bootstrap_file($_bootstrap_file)
	{
		$this->_bootstrap_file = $_bootstrap_file;
		return $this;
	}

	public function set_functions_file($_functions_file)
	{
		$this->_functions_file = $_functions_file;
		return $this;
	}

	public function get_bootstrap_file()
	{
		return $this->_bootstrap_file;
	}
	
	public function get_functions_file()
	{
		return $this->_functions_file;
	}

	public function get_config_dir()
	{
		return $this->_config_dir;
	}

	public function get_views_dir()
	{
		return $this->_views_dir;
	}

}

/**
 * EOF
 */