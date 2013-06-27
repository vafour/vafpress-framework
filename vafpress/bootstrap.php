<?php

//////////////////////////
// Include Constants    //
//////////////////////////
require_once 'constant.php';

//////////////////////////
// Include Autoloader  //
//////////////////////////
require_once 'autoload.php';

//////////////////////////
// Bootstrap Extensions //
//////////////////////////
foreach (glob(VP_EXT_DIR . "/*", GLOB_ONLYDIR) as $ext)
{
	$bs_file  = $ext . '/bootstrap.php';
	$fc_file  = $ext . '/functions.php';
	if(is_file($bs_file) and is_file($fc_file))
	{
		// bootstrap and get namespace
		$ns = require_once $bs_file;

		// check the existence of config and views dir
		$views_dir  = is_dir($ext . '/views')  ? $ext . '/views'  : '';
		$config_dir = is_dir($ext . '/config') ? $ext . '/config' : '';

		VP_Extension::add_extension($ns)
			->set_bootstrap_file($bs_file)
			->set_functions_file($fc_file)
			->set_views_dir($views_dir)
			->set_config_dir($config_dir);
	}
}


//////////////////////////
// Setup FileSystem     //
//////////////////////////
$vpfs = VP_FileSystem::instance();
// App Directories
$vpfs->add_directories('views'   , VP_APP_VIEWS_DIR);
$vpfs->add_directories('builder' , VP_APP_BUILDER_DIR);
$vpfs->add_directories('config'  , VP_APP_CONFIG_DIR);
$vpfs->add_directories('data'    , VP_APP_DATA_DIR);
$vpfs->add_directories('includes', VP_APP_INCLUDE_DIR);
// Core Directories
$vpfs->add_directories('views'   , VP_CORE_VIEWS_DIR);
$vpfs->add_directories('builder' , VP_CORE_BUILDER_DIR);
$vpfs->add_directories('config'  , VP_CORE_CONFIG_DIR);
$vpfs->add_directories('data'    , VP_CORE_DATA_DIR);
$vpfs->add_directories('includes', VP_CORE_INCLUDE_DIR);


//////////////////////////
// Include Data Source  //
//////////////////////////
foreach (array_merge(glob(VP_APP_DATA_DIR . "/*.php"), glob(VP_CORE_DATA_DIR . "/*.php")) as $datasource)
{
	require_once($datasource);
}

////////////////////////
// Global Variables   //
////////////////////////
global $vp_set, $vp_config, $vp_opt;
$vp_opt = array();

////////////////////////
// Load Theme Config  //
////////////////////////
$vp_config = VP_Util_Config::instance()->load('option');


////////////////////////
// Load Languages     //
////////////////////////
$lang_dir = VP_THEME_DIR . '/lang';
load_theme_textdomain('vp_textdomain', $lang_dir);

/////////////////////////////
// Bootstrap Theme Options //
/////////////////////////////
require_once 'option.php';

/////////////////////////
// Bootstrap Metaboxes //
/////////////////////////
require_once 'metabox.php';

////////////////////////
// Run Extension      //
////////////////////////
foreach (VP_Extension::get_extensions() as $ext)
{
	require_once $ext->get_functions_file();
}

///////////////////////////////////////////////
// Theme Activation and Deactivation actions //
///////////////////////////////////////////////
function vp_deactivate_theme()
{
	// get new theme slug
	$new_theme = get_stylesheet();

	// delete old opt
	$stylesheet = get_option( 'theme_switched' );
	delete_option("vpf_active_$stylesheet");

	// send data if it's not vpf theme activated
	if(!get_option("vpf_active_$new_theme"))
	{
		if(vp_should_track())
		{
			$tracker    = new VP_WP_Tracker();
			$tracker->track($stylesheet);
		}
    }
}

function vp_activate_theme()
{
	// set activated option value
	$theme      = get_stylesheet();
	$option_key = 'vpf_active_' . $theme;
	update_option( $option_key, 1 );

	// setup option to db
	vp_setup_options_to_db();

	if(vp_should_track())
	{
		$tracker = new VP_WP_Tracker();
		$tracker->track();
	}
}

function vp_should_track()
{
	if(vp_option('enable_tracking') and !vp_is_local())
	{
		return true;
	}
	return false;
}

function vp_is_local()
{
	$local_hosts = array('localhost', '127.0.0.1', '::1');
	if(isset($_SERVER['HTTP_HOST']) and !in_array($_SERVER['HTTP_HOST'], $local_hosts))
		return false;
	return true;
}

// schedule tracker
if(vp_should_track())
{
	$tracker = new VP_WP_Tracker();
	$tracker->schedule_track();
}

// register theme deactivation hook
add_action('switch_theme', 'vp_deactivate_theme');

// register theme activation 'hook'
add_action('after_switch_theme', 'vp_activate_theme');

// do scripts and styles dependencies for Mass Enqueuer
VP_WP_MassEnqueuer::instance()->register();

/**
 * EOF
 */