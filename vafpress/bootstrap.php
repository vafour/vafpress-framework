<?php

/**
 * CONSTANTS
 */
define('VP_VERSION', '2.0');

define('VP_THEME_DIR'  , get_template_directory());
define('VP_DIR'        , VP_THEME_DIR . '/vafpress');
define('VP_CONFIG_DIR' , VP_DIR . '/config');
define('VP_BUILDER_DIR', VP_DIR . '/builder');
define('VP_CLASSES_DIR', VP_DIR . '/classes');
define('VP_VIEWS_DIR'  , VP_DIR . '/views');
define('VP_IMAGE_DIR'  , VP_DIR . '/public/img');
define('VP_INCLUDE_DIR', VP_DIR . '/includes');

define('VP_THEME_URL'  , get_template_directory_uri());
define('VP_URL'        , VP_THEME_URL . '/vafpress');
define('VP_PUBLIC_URL' , VP_URL . '/public');
define('VP_IMAGE_URL'  , VP_PUBLIC_URL . '/img');
define('VP_INCLUDE_URL', VP_URL . '/includes');

// Get the start time and memory for use later
defined('VP_START_TIME') or define('VP_START_TIME', microtime(true));
defined('VP_START_MEM')  or define('VP_START_MEM',  memory_get_usage());

//////////////////////////
// Include Auotoloader  //
//////////////////////////
require_once VP_DIR . '/autoload.php';


//////////////////////////
// Include Data Source  //
//////////////////////////
require_once VP_DIR . '/data/sources.php';


////////////////////////
// Load Theme Config  //
////////////////////////
$config = VP_Util_Config::get_instance()->load('option');


////////////////////////
// Load Languages     //
////////////////////////
$lang_dir = VP_THEME_DIR . '/lang';
load_theme_textdomain('vp_textdomain', $lang_dir);


/////////////////////////
// Parsing the option  //
/////////////////////////
try {
	// loading the file, if doesn't exists try to load .sample version
	$option_path        = VP_BUILDER_DIR . '/option/option.php';
	$option_path_sample = $option_path . '.sample';
	if(file_exists($option_path))
		$options = include($option_path);
	else
		$options = include($option_path_sample);
} catch (Exception $e){
	echo $e->getMessage();
}
$parser  = new VP_Option_Parser();
$set	 = $parser->parse_array_options($options);

////////////////////////////////////////////////
// Add Import and Export Option Functionality //
////////////////////////////////////////////////
if(VP_Util_Config::get_instance()->load('option', 'impexp'))
{
	$ie_menu    = new VP_Option_Control_Group_Menu();
	$ie_field   = new VP_Option_Control_Field_ImpExp();

	$ie_menu->set_title(__('Import and Export', 'vp_textdomain'));
	$ie_menu->set_name('impexp');
	$ie_menu->set_icon('font-awesome:icon-wrench');
	$ie_menu->add_control($ie_field);

	$set->add_menu($ie_menu);
}

////////////////////
// Load Metaboxes //
////////////////////
require_once 'metabox.php';


//////////////////////////////////////////
// Load Options to be used in the Theme //
//////////////////////////////////////////
/**
 * @todo load default values, and then check on db, if not available then save to the db
 * @todo load option from db and expose them to be used on theme
 */
global $opt;

// try load option from DB
$db_options = get_option($config['option_key']);
$default    = $set->get_defaults();
if (!empty($db_options))
{
	// unify, preserve option from DB but appends anything new from default
	$opt = $db_options;
	$opt = $opt + $default;
}
else
{
	$opt = $set->get_defaults();
	update_option($config['option_key'], $opt);
}

// If dev mode, always use default, no db interaction
if($config['dev_mode'])
	$opt = $set->get_defaults();

// populate option to fields' values
$set->populate_values($opt);

// process binding
$set->process_binding();

// process dependencies
$set->process_dependencies();

// helper function to obtain option value
function vp_option($key)
{
	global $opt;
	if(array_key_exists($key, $opt))
	{
		return $opt[$key];
	}
	return null;
}


///////////////////////////////
// Theme Menu and Page Setup //
///////////////////////////////
function vafpress_theme_menu()
{
	global $set;
	global $config;
	add_theme_page(
		$set->get_title(),         // The title to be displayed in the browser window for this page.
		$set->get_page(),          // The text to be displayed for this menu item
		$config['role'],           // Which type of users can see this menu item
		$config['menu_page_slug'], // The unique ID - that is, the slug - for this menu item
		'vafpress_theme_display'   // The name of the function to call when rendering the page for this menu
	);
}

function vafpress_theme_display()
{
	// render the page
	global $set;
	echo $set->render();
}
add_action('admin_menu', 'vafpress_theme_menu');

// load scripts and styles dependencies
$opt_loader      = new VP_WP_Loader();
$opt_deps_loader = new VP_Option_Depsloader($set);
$opt_loader->register($opt_deps_loader);

// development mode notice
add_action('admin_notices', 'vp_opt_notice_devmode');

function vp_opt_notice_devmode($hook_suffix)
{
	global $opt_deps_loader;
	global $hook_suffix;

	if(VP_Util_config::get_instance()->load('option', 'dev_mode'))
	{
		if($opt_deps_loader->can_output($hook_suffix))
		{
	    	VP_WP_Util::admin_notice(__("[Vafpress Framework] Theme Option Development Mode is Active, value won't be saved into database.", 'vp_textdomain'), false);
		}
	}
}


///////////////////////////////////////////////
// Theme Activation and Deactivation actions //
///////////////////////////////////////////////
function vp_deactivate_theme()
{
	// get new theme slug
	$new_theme = wp_get_theme();
	$new_theme = $new_theme->get_stylesheet();

	// delete old opt
	$stylesheet = get_option( 'theme_switched' );
	delete_option("vpf_active_$stylesheet");

	// send data if it's not vpf theme activated
	if(!get_option("vpf_active_$new_theme"))
	{
		$tracker    = new VP_WP_Tracker();
		$tracker->track($stylesheet);
    }
}

function vp_activate_theme()
{
	// set activated option value
	$theme      = wp_get_theme();
	$theme      = $theme->get_stylesheet();
	$option_key = 'vpf_active_' . $theme;
	update_option( $option_key, 1 );

    $tracker = new VP_WP_Tracker();
    $tracker->track();
}

$local_hosts = array('localhost', '127.0.0.1', '::1');
$local_hosts = array();
if(isset($_SERVER['HTTP_HOST']) and !in_array($_SERVER['HTTP_HOST'], $local_hosts))
{
	// register theme deactivation hook
	add_action('switch_theme', 'vp_deactivate_theme');

	// register theme activation 'hook'
	add_action('after_switch_theme', 'vp_activate_theme');
}


//////////////////////
// Ajax Admin Logic //
//////////////////////
add_action('wp_ajax_vp_ajax_save'         , 'vp_ajax_save');
add_action('wp_ajax_vp_ajax_export_option', 'vp_ajax_export_option');
add_action('wp_ajax_vp_ajax_import_option', 'vp_ajax_import_option');
add_action('wp_ajax_vp_ajax_wrapper'      , 'vp_ajax_wrapper');

function vp_ajax_wrapper()
{
	$function = $_POST['func'];
	$params   = $_POST['params'];

	if(!is_array($params))
		$params = array($params);

	try {
		$result['data']    = call_user_func_array($function, $params);
		$result['status']  = true;
		$result['message'] = __("", 'vp_textdomain');
	} catch (Exception $e) {
		$result['data']    = '';
		$result['status']  = false;
		$result['message'] = $e->getMessage();		
	}

	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_save()
{
	global $set;
	global $config;

	$option = $_POST['option'];
	$nonce  = $_POST['nonce'];

	$option = VP_Util_Array::unite( $option, 'name', 'value' );
	$option = $set->normalize_values($option);

	$set->populate_values($option, true);

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		$result = $set->save($config['option_key']);
	}

	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_import_option()
{
	global $set;
	global $config;

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		$option = $_POST['option'];
		if(empty($option))
		{
			$result['status']  = false;
			$result['message'] = __("Can't be empty.", 'vp_textdomain');
		}
		else
		{
			$option = maybe_unserialize(stripslashes($option));
			if( is_array($option) )
			{
				$set->populate_values($option, true);
				$result = $set->save($config['option_key']);
			}
			else
			{
				$result['status']  = false;
				$result['message'] = __("Invalid data.", 'vp_textdomain');
			}
		}
	}

	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_export_option()
{
	global $config;

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		$db_options = get_option($config['option_key']);
		$db_options = serialize($db_options);
		$result = array(
			'status' => true,
			'message'=> __("", 'vp_textdomain'),
			'option' => $db_options
		);
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_verify_nonce()
{
	$nonce  = $_POST['nonce'];
	$verify = check_ajax_referer('vafpress', 'nonce', false);
	if($verify)
	{
		$result['status']  = true;
		$result['message'] = __("", 'vp_textdomain');	
	}
	else
	{
		$result['status']  = false;
		$result['message'] = __("Unverified Access.", 'vp_textdomain');
	}
	return $result;
}

/**
 * EOF
 */