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

	private $_view_dir;

	private $_view_ext;

	private $_views;

	private function __construct()
	{
		$this->_view_dir = VP_THEME_DIR . '/vafpress/view/';
		$this->_view_ext = '.php';
		$this->_views    = array();
	}

	public static function get_instance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load view file
	 * @param	String $field_view_file Name of the view file
	 * @param	Array $data Array of data to be binded on the view
	 * @return String The result view
	 */
	public function load($field_view_file, Array $data)
	{
		if (array_key_exists('field_view_file', $data))
		{
			throw new Exception("Sorry 'field_view_file' variable name can't be used.");
		}
		if (array_key_exists($field_view_file, $this->_views))
		{
			$view = $this->_views[$field_view_file];
		}
		else
		{
			$view_file = $this->_view_dir . $field_view_file . $this->_view_ext;
			$view = file_get_contents($view_file);
			$this->_views[$field_view_file] = $view;
		}
		$view = ' ?>' . $view . '<?php ';

		extract($data);
		ob_start();
		eval($view);
		
		return ob_get_clean();
	}

}

/**
 * EOF
 */