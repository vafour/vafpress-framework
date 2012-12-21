<?php

/**
 * CONSTANTS
 */
define('VP_VERSION' , '1.0a');

define('VP_THEME_DIR' , get_template_directory());
define('VP_DIR'       , VP_THEME_DIR . '/vafpress');
define('VP_CONFIG_DIR', VP_DIR . '/config');
define('VP_IMAGE_DIR' , VP_DIR . '/public/img');

define('VP_THEME_URL' , get_template_directory_uri());
define('VP_URL'       , VP_THEME_URL . '/vafpress');
define('VP_PUBLIC_URL', VP_URL . '/public');
define('VP_IMAGE_URL' , VP_PUBLIC_URL . '/img');

// Get the start time and memory for use later
defined('VP_START_TIME') or define('VP_START_TIME', microtime(true));
defined('VP_START_MEM')  or define('VP_START_MEM',  memory_get_usage());

//////////////////////////
// Include Auotoloader  //
//////////////////////////
require VP_DIR . '/autoload.php';


//////////////////////////
// Include Data Source  //
//////////////////////////
require_once VP_DIR . '/datasources.php';


////////////////////////
// Load Theme Config  //
////////////////////////
$config = VP_Util_Config::get_instance()->load('vafpress');


////////////////////////
// Load Languages     //
////////////////////////
$lang_dir = VP_THEME_DIR . '/lang';
load_theme_textdomain('vp_textdomain', $lang_dir);


/////////////////////////
// Parsing the option  //
/////////////////////////
$options = include(VP_CONFIG_DIR . '/option.php');
$parser  = new VP_Option_Parser();
$set	 = $parser->parse_array_options($options);


////////////////////////////////////////////////
// Add Import and Export Option Functionality //
////////////////////////////////////////////////
$ie_menu    = new VP_Option_Group_Menu();
$ie_section = new VP_Option_Group_Section();
$ie_field   = new VP_Option_Field_ImpExp();

$ie_menu->set_title(__('Import and Export', 'vp_textdomain'));
$ie_menu->set_name('impexp');
$ie_menu->set_icon('/icon/impexp.png');

$ie_section->set_name('impexpt_section');

$ie_section->add_field($ie_field);
$ie_menu->add_section($ie_section);
$set->add_menu($ie_menu);


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
	$opt = $db_options;
	// unify, preserve opt but appends anything new from default
	$opt = $opt + $default;
}
else
{
	$opt = $set->get_defaults();
	update_option($config['option_key'], $opt);
}

// If dev mode, always use default, no db interaction first
if($config['dev_mode'])
	$opt = $set->get_defaults();

// populate option to fields' values
$set->populate_values($opt);

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


/////////////////////////////////////
// Scripts and Styles Registration //
/////////////////////////////////////
function vp_scripts_and_styles($hook_suffix)
{
	global $config, $set;

	$use_upload           = false;
	$use_new_media_upload = false;

	// if not in option page, don't load
	if( $hook_suffix !== ('appearance_page_' . $config['menu_page_slug']) )
		return;

	// dynamic loading, load only what needed
	$script_deps = array('jquery', 'prefixfree-js', 'scrollspy-js', 'tipsy-js');
	$style_deps  = array('tipsy-css');
	$rules = array(
		'color'       => array( 'js' => array('colorpicker-js'), 'css' => array('colorpicker-css') ),
		'select'      => array( 'js' => array('chosen-js'), 'css' => array('chosen-css') ),
		'multiselect' => array( 'js' => array('chosen-js'), 'css' => array('chosen-css') ),
		'slider'      => array( 'js' => array('jquery-ui-slider'), 'css' => array('jqui') ),
		'date'        => array( 'js' => array('jquery-ui-datepicker'), 'css' => array('jqui') ),
	);
	
	$fields = $set->get_fields();
	foreach ($fields as $field)
	{
		$type = VP_Util_Text::field_type_from_class(get_class($field));
		if( array_key_exists($type, $rules) )
		{
			$script_deps = array_merge($script_deps, $rules[$type]['js']);
			$style_deps  = array_merge($style_deps, $rules[$type]['css']);
		}
		// check if using upload button
		if( $type == 'upload' )
		{
			$use_upload = true;
		}
	}
	$script_deps = array_unique($script_deps);
	$style_deps  = array_unique($style_deps);

	if($use_upload)
	{
		wp_enqueue_media();

		global $wp_version;
		if (version_compare($wp_version, '3.5', '<')) {
			// version is under 3.5
			$script_deps[] = 'thickbox';
			$style_deps[]  = 'thickbox';
		}
		else
		{
			$use_new_media_upload = true;
		}
	}

	wp_register_script('colorpicker-js', VP_PUBLIC_URL . '/js/vendor/colorpicker.js', array('jquery'), '', true);
	wp_register_script('tipsy-js', VP_PUBLIC_URL . '/js/vendor/jquery.tipsy.js', array('jquery'), '', true);
	wp_register_script('chosen-js', VP_PUBLIC_URL . '/js/vendor/chosen.jquery.min.js', array('jquery'), '', true);
	wp_register_script('prefixfree-js', VP_PUBLIC_URL . '/js/vendor/prefixfree.min.js', array('jquery'), '', true);
	wp_register_script('scrollspy-js', VP_PUBLIC_URL . '/js/vendor/jquery-scrollspy.js', array('jquery'), '', true);
	wp_register_script('admin', VP_PUBLIC_URL . '/js/admin.js', $script_deps, '', true);
	wp_enqueue_script('admin');

	// Localization + Extra Variables passed to the main JS
	$messages = VP_Util_Config::get_instance()->load('messages');
	$extra = array(
		'vp_public_url'        => VP_PUBLIC_URL,
		'val_msg'              => $messages['validation'],
		'impexp_msg'           => $messages['impexp'],
		'use_new_media_upload' => $use_new_media_upload,
	);
	wp_localize_script( 'admin', 'vp', $extra );

	$jqui_theme = "smoothness";
	wp_register_style('colorpicker-css', VP_PUBLIC_URL . '/css/vendor/colorpicker.css');
	wp_register_style('tipsy-css', VP_PUBLIC_URL . '/css/vendor/tipsy.css');
	wp_register_style('chosen-css', VP_PUBLIC_URL . '/css/vendor/chosen.css');
	wp_register_style('jqui',  VP_PUBLIC_URL . '/css/vendor/jqueryui/themes/' . $jqui_theme . '/jquery-ui-1.9.2.custom.min.css');
	wp_register_style('admin-css', VP_PUBLIC_URL . '/css/admin.css', $style_deps, '', false);
	wp_enqueue_style('admin-css');
}
add_action('admin_enqueue_scripts', 'vp_scripts_and_styles');


//////////////////////
// Ajax Admin Logic //
//////////////////////
add_action('wp_ajax_vp_ajax_admin', 'vp_ajax_admin');
add_action('wp_ajax_vp_ajax_export_option', 'vp_ajax_export_option');
add_action('wp_ajax_vp_ajax_import_option', 'vp_ajax_import_option');

function vp_ajax_admin()
{
	global $set;
	global $config;

	$option = $_POST['option'];
	$option = VP_Util_Array::unite( $option, 'name', 'value' );
	$option = $set->normalize_values($option);
	$set->populate_values($option);

	$result = $set->save($config['option_key']);
	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_import_option()
{
	global $set;
	global $config;

	header('Content-type: application/json');

	$option = $_POST['option'];
	$option = maybe_unserialize(stripslashes($option));
	if( !is_array($option) )
		$option = array();

	$set->populate_values($option);

	$result = $set->save($config['option_key']);
	echo json_encode($result);
	die();
}

function vp_ajax_export_option()
{
	global $config;
	$db_options = get_option($config['option_key']);
	$db_options = serialize($db_options);
	header('Content-type: application/json');
	$result = array('option' => $db_options);
	echo json_encode($result);
	die();
}

/**
 * EOF
 */