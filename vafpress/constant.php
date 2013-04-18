<?php

/*
|--------------------------------------------------------------------------
| Vafpress Framework Constants
|--------------------------------------------------------------------------
*/
define('VP_VERSION'  , '2.0');
define('VP_NAMESPACE', 'VP_');

if(function_exists('get_template_directory_uri'))
{
	define('VP_THEME_DIR'   , get_template_directory());
}
else
{
	define('VP_THEME_DIR'   , dirname(__FILE__) . '/..');
}
define('VP_DIR'             , VP_THEME_DIR . '/vafpress');
define('VP_IMAGE_DIR'       , VP_DIR . '/public/img');

define('VP_APP_DIR'         , VP_DIR . '/app');
define('VP_CORE_DIR'        , VP_DIR . '/core');
define('VP_EXT_DIR'         , VP_DIR . '/extension');

define('VP_CORE_CONFIG_DIR' , VP_CORE_DIR . '/config');
define('VP_CORE_BUILDER_DIR', VP_CORE_DIR . '/builder');
define('VP_CORE_DATA_DIR'   , VP_CORE_DIR . '/data');
define('VP_CORE_CLASSES_DIR', VP_CORE_DIR . '/classes');
define('VP_CORE_VIEWS_DIR'  , VP_CORE_DIR . '/views');
define('VP_CORE_INCLUDE_DIR', VP_CORE_DIR . '/includes');

define('VP_APP_CONFIG_DIR'  , VP_APP_DIR . '/config');
define('VP_APP_BUILDER_DIR' , VP_APP_DIR . '/builder');
define('VP_APP_DATA_DIR'    , VP_APP_DIR . '/data');
define('VP_APP_CLASSES_DIR' , VP_APP_DIR . '/classes');
define('VP_APP_VIEWS_DIR'   , VP_APP_DIR . '/views');
define('VP_APP_INCLUDE_DIR' , VP_APP_DIR . '/includes');

if(function_exists('get_template_directory_uri'))
{
	define('VP_THEME_URL'       , get_template_directory_uri());
	define('VP_URL'             , VP_THEME_URL  . '/vafpress');
	define('VP_PUBLIC_URL'      , VP_URL        . '/public');
	define('VP_IMAGE_URL'       , VP_PUBLIC_URL . '/img');
	define('VP_INCLUDE_URL'     , VP_URL        . '/includes');
	define('VP_EXTENSION_URL'   , VP_URL        . '/extension');
}

// Get the start time and memory usage for profiling
defined('VP_START_TIME') or define('VP_START_TIME', microtime(true));
defined('VP_START_MEM')  or define('VP_START_MEM',  memory_get_usage());

/**
 * EOF
 */