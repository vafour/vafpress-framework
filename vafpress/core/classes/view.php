<?php

/**
 * A Singleton class for loading view template
 */
class VP_View
{

	/**
	 * Singleton instance of the class
	 * @var Option_View
	 */
	private static $_instance;

	private $_views;

	private function __construct()
	{
		$this->_views    = array();
	}

	public static function instance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load view file
	 * @param  String $field_view_file Name of the view file
	 * @param  Array $data Array of data to be binded on the view
	 * @return String The result view
	 */
	public function load($field_view_file, $data = array())
	{
		if (array_key_exists('field_view_file', $data))
		{
			throw new Exception("Sorry 'field_view_file' variable name can't be used.");
		}

		// check if it's belong to extension
		$ns = VP_AutoLoader::discover_namespace($field_view_file);
		if($ns !== '')
		{
			$ext       = VP_Extension::get_extension($ns);
			$view_file = str_replace($ns, '', $field_view_file);

			if(is_file($ext->get_views_dir() . '/'. $view_file . '.php'))
			{
				$view_file = $ext->get_views_dir() . '/'. $view_file . '.php';
			}
			else
			{
				$view_file = false;
			}
		}
		else
		{
			$view_file = VP_FileSystem::instance()->resolve_path('views', $field_view_file);
		}

		if($view_file === false)
		{
			throw new Exception("View file not found.");
		}
	
		extract($data);
		ob_start();
		include $view_file;
		return ob_get_clean();
	}

}

/**
 * EOF
 */