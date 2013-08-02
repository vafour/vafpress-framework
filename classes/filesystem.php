<?php

class VP_FileSystem
{

	private static $_instance = null;

	private $_lookup_dirs = array();

	public static function instance()
	{
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function get_first_non_empty_dir($key, $name = null)
	{
		if(!isset($this->_lookup_dirs[$key]))
			return false;

		foreach ($this->_lookup_dirs[$key] as $dir)
		{
			if (is_link($dir))
			{
				$dir = readlink($dir);
			}
			if(!is_null($name))
			{
				$dir = $dir . DIRECTORY_SEPARATOR . $name;
			}
			if($this->dir_contains_children($dir, 'php'))
			{
				return $dir;
			}
		}
	}

	public function resolve_path($key, $name, $ext = 'php')
	{
		if(!isset($this->_lookup_dirs[$key]))
			return false;

		$name = $this->normalize_path($name, $ext);

		foreach ($this->_lookup_dirs[$key] as $dir)
		{
			$file = $dir . DIRECTORY_SEPARATOR . $name;
			if (is_link($file))
			{
				$file = readlink($file);
			}
			if(file_exists($file))
			{
				return $file;
			}
		}
		return false;
	}

	public function normalize_path($path, $ext)
	{
		$path = trim($path, '\\/');
		return $path . '.' . $ext;
	}

	function dir_contains_children($dir, $ext = null)
	{
		$result = false;
		if (is_link($dir))
		{
			$dir = readlink($dir);
		}

		if($dh = opendir($dir))
		{
			while(!$result && ($file = readdir($dh)) !== false)
			{
				$result = $file !== "." && $file !== "..";
				if(!is_null($ext))
				{
					$result = pathinfo($file, PATHINFO_EXTENSION) === $ext;
				}
			}
			closedir($dh);
		}
		return $result;
	}

	/**
	 * Add directories to the autoloader, loading process will be run in orderly fashion
	 * of directory addition.
	 * 
	 * @param  String|Array $directories
	 * @return void
	 */
	public function add_directories($key, $directories)
	{
		if(!isset($this->_lookup_dirs[$key]))
		{
			$this->_lookup_dirs[$key] = array();
		}
		$this->_lookup_dirs[$key] = array_merge($this->_lookup_dirs[$key], (array) $directories);
		$this->_lookup_dirs[$key] = array_unique($this->_lookup_dirs[$key]);
	}

	/**
	 * Remove directories.
	 * 
	 * @param  String|Array $directories
	 * @return void
	 */
	public function remove_directories($key, $directories = null)
	{
		// annihilate everything if none / null passed
		if(is_null($directories))
		{
			$this->_lookup_dirs[$key] = array();
		}
		else
		{
			// prepare directories to be filtered
			$directories = (array) $directories;

			// do the filtering
			foreach ($this->_lookup_dirs[$key] as $name => $dir)
			{
				if(in_array($dir, $directories))
				{
					unset($this->_lookup_dirs[$key][$name]);
				}
			}
		}
	}

	/**
	 * Get all directories
	 * 
	 * @return Array
	 */
	public function get_directories($key = null)
	{
		if(!is_null($key))
			return $this->_lookup_dirs[$key];
		return $this->_lookup_dirs;
	}

}