<?php

/*
|--------------------------------------------------------------------------
| Register AutoLoader
|--------------------------------------------------------------------------
| Vafpress Framework has separated app and core directories, developers can
| put their extension code and configuration at app folder, as everything
| inside app will be loaded first and will override class with the same
| name with core classes.
*/
VP_AutoLoader::add_directories(VP_APP_CLASSES_DIR);
VP_AutoLoader::add_directories(VP_CORE_CLASSES_DIR);
VP_AutoLoader::register();

class VP_AutoLoader
{

	/**
	 * Indicates if VP_AutoLoader has been registered.
	 * 
	 * @var boolean
	 */
	protected static $registered = false;

	/**
	 * The registered directories
	 * 
	 * @var array
	 */
	protected static $directories = array();

	/**
	 * Autoloading logic
	 * 
	 * @param  String  $class Class name
	 * @return Boolean        Whether the loading succeded.
	 */
	public static function load($class)
	{
		// halt process if not in our namespace
		if (strpos($class, VP_NAMESPACE) !== 0) {
			return;
		}
		$class = self::normalize_class($class);

		foreach (self::$directories as $dir)
		{
			$file = $dir . DIRECTORY_SEPARATOR . $class;
			if(file_exists($file))
			{
				// echo '<br/>';
				// echo $file . ' loaded.';
				require_once $file;
				return true;
			}
		}
	}

	/**
	 * Register autoloader
	 * 
	 * @return void
	 */
	public static function register()
	{
		if(self::$registered !== TRUE)
		{
			spl_autoload_register(array('VP_AutoLoader', 'load'));
		}
		self::$registered = TRUE;
	}

	/**
	 * Add directories to the autoloader, loading process will be run in orderly fashion
	 * of directory addition.
	 * 
	 * @param  String|Array $directories
	 * @return void
	 */
	public static function add_directories($directories)
	{
		self::$directories = array_merge(self::$directories, (array) $directories);
		self::$directories = array_unique(self::$directories);
	}

	/**
	 * Remove directories.
	 * 
	 * @param  String|Array $directories
	 * @return void
	 */
	public static function remove_directories($directories = null)
	{
		// annihilate everything if none / null passed
		if(is_null($directories))
		{
			self::$directories = array();
		}
		else
		{
			// prepare directories to be filtered
			$directories = (array) $directories;

			// do the filtering
			foreach (self::$directories as $key => $dir)
			{
				if(in_array($dir, $directories))
				{
					unset(self::$directories[$key]);
				}
			}
		}
	}

	/**
	 * Normalize class to be loaded
	 * 
	 * @param  String $class Class name
	 * @return String        Normalized class name
	 */
	public static function normalize_class($class)
	{
		$class = ltrim($class, '\\');
		$class = str_replace(VP_NAMESPACE, '', $class);
		$class = ltrim($class, '_');
		$class = strtolower($class);
		return str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
	}

	/**
	 * Get all directories
	 * 
	 * @return Array
	 */
	public static function get_directories()
	{
		return self::$directories;
	}

}

/**
 * EOF
 */