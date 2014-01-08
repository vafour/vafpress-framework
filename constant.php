<?php

/*
|--------------------------------------------------------------------------
| Vafpress Framework Constants
|--------------------------------------------------------------------------
*/

defined('VP_VERSION')     or define('VP_VERSION'    , '2.0-beta');
defined('VP_NAMESPACE')   or define('VP_NAMESPACE'  , 'VP_');
defined('VP_DIR')         or define('VP_DIR'        , untrailingslashit(dirname(__FILE__)));
defined('VP_DIR_NAME')    or define('VP_DIR_NAME'   , basename(VP_DIR));
defined('VP_IMAGE_DIR')   or define('VP_IMAGE_DIR'  , VP_DIR . '/public/img');
defined('VP_CONFIG_DIR')  or define('VP_CONFIG_DIR' , VP_DIR . '/config');
defined('VP_DATA_DIR')    or define('VP_DATA_DIR'   , VP_DIR . '/data');
defined('VP_CLASSES_DIR') or define('VP_CLASSES_DIR', VP_DIR . '/classes');
defined('VP_VIEWS_DIR')   or define('VP_VIEWS_DIR'  , VP_DIR . '/views');
defined('VP_INCLUDE_DIR') or define('VP_INCLUDE_DIR', VP_DIR . '/includes');

// get and normalize framework dirname
$dirname        = str_replace('\\' ,'/', dirname(__FILE__)); // standardize slash
$dirname        = preg_replace('|/+|', '/', $dirname);       // normalize duplicate slash

// get and normalize WP content directory
$wp_content_dir = str_replace( '\\', '/', WP_CONTENT_DIR );  // standardize slash

// build relative url
$relative_url   = str_replace($wp_content_dir, "", $dirname);

// finally framework base url
$vp_url         = content_url() . $relative_url;

defined('VP_URL')         or define('VP_URL'        , untrailingslashit($vp_url));
defined('VP_PUBLIC_URL')  or define('VP_PUBLIC_URL' , VP_URL        . '/public');
defined('VP_IMAGE_URL')   or define('VP_IMAGE_URL'  , VP_PUBLIC_URL . '/img');
defined('VP_INCLUDE_URL') or define('VP_INCLUDE_URL', VP_URL        . '/includes');

// Get the start time and memory usage for profiling
defined('VP_START_TIME')  or define('VP_START_TIME', microtime(true));
defined('VP_START_MEM')   or define('VP_START_MEM',  memory_get_usage());

/**
 * EOF
 */