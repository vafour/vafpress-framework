<?php

// Class Autoloading Logic

function vp_autoload($className)
{
	if (strpos($className, "VP_") !== 0) {
		return;
	}
	$className = ltrim($className, '\\');
	$fileName	= '';
	$namespace = 'VP_';
	$className = str_replace($namespace, '', $className);
	$className = strtolower($className);
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	require VP_CLASSES_DIR . '/' . $fileName;
}

spl_autoload_register('vp_autoload');

/**
 * End of autoload.php
 */