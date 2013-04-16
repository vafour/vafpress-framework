<?php

//////////////////////////
// Include Constants    //
//////////////////////////
require_once 'constants.php';


//////////////////////////
// Include Autoloader  //
//////////////////////////
require_once 'autoload.php';

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
// Load Theme Config  //
////////////////////////
$config = VP_Util_Config::instance()->load('option');


////////////////////////
// Load Languages     //
////////////////////////
$lang_dir = VP_THEME_DIR . '/lang';
load_theme_textdomain('vp_textdomain', $lang_dir);

// $set = new VP_Option_Control_Set();
$set;
$opt = array();

// get options for db
vp_init_options_db();

// if is ajax wrapper request, don't parse option page
if( !(vp_is_ajax() and isset($_POST['action']) and $_POST['action'] === 'vp_ajax_wrapper') )
{
	add_action('admin_enqueue_scripts', 'vp_setup');
}
add_action('admin_menu', 'vafpress_theme_menu');


function vp_setup()
{
	global $set, $opt;
	$screen = get_current_screen();

	// if we are at option page
	if(vp_is_option_page($screen->id))
	{
		// parse options set object
		vp_init_option_set();
		// init options with $set defaults merging
		vp_init_options();
		// load scripts and styles
		vp_load_scripts_and_styles();
	}
}

function vp_is_option_page($hook_suffix)
{
	$menu_page_slug = VP_Util_Config::instance()->load('option', 'menu_page_slug');
	if( $hook_suffix == ('appearance_page_' . $menu_page_slug) )
		return true;
	return false;
}

function vp_init_option_set()
{
	global $set;

	// Parse the option
	try{
		$option_path = VP_FileSystem::instance()->resolve_path('builder', 'option/option');
		$options     = include($option_path);
	} catch (Exception $e){
		echo $e->getMessage();
	}
	$parser = new VP_Option_Parser();
	$set	= $parser->parse_array_options($options);

	// Add Import and Export Option Functionality
	if(VP_Util_Config::instance()->load('option', 'impexp'))
	{
		$ie_menu    = new VP_Option_Control_Group_Menu();
		$ie_field   = new VP_Option_Control_Field_ImpExp();

		$ie_menu->set_title(__('Import and Export', 'vp_textdomain'));
		$ie_menu->set_name('impexp');
		$ie_menu->set_icon('font-awesome:icon-wrench');
		$ie_menu->add_control($ie_field);

		$set->add_menu($ie_menu);
	}
}


////////////////////
// Load Metaboxes //
////////////////////
require_once 'metabox.php';


////////////////////////////////////////////
// Load Options to be used in option page //
////////////////////////////////////////////
/**
 * @todo load default values, and then check on db, if not available then save to the db
 * @todo load option from db and expose them to be used on theme
 */
function vp_init_options()
{
	global $config;
	global $set;

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
}

function vp_init_options_db()
{
	global $config, $opt;

	// try load option from DB
	$db_options = get_option($config['option_key']);
	if (!empty($db_options))
	{
		$opt = $db_options;
	}
}

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
		$config['browser_page_title'], // The title to be displayed in the browser window for this page.
		$config['menu_page_label'],    // The text to be displayed for this menu item
		$config['role'],               // Which type of users can see this menu item
		$config['menu_page_slug'],     // The unique ID - that is, the slug - for this menu item
		'vafpress_theme_display'       // The name of the function to call when rendering the page for this menu
	);
}

function vafpress_theme_display()
{
	// render the page
	global $set;
	echo $set->render();
}

function vp_load_scripts_and_styles()
{
	global $set;
	global $opt_deps_loader;

	// load scripts and styles dependencies
	$opt_loader      = new VP_WP_Loader();
	$opt_deps_loader = new VP_Option_Depsloader($set);
	$opt_loader->register($opt_deps_loader);
}

// development mode notice
add_action('admin_notices', 'vp_opt_notice_devmode');

function vp_opt_notice_devmode($hook_suffix)
{
	global $opt_deps_loader;
	global $hook_suffix;

	if(VP_Util_config::instance()->load('option', 'dev_mode'))
	{
		if( vp_is_option_page($hook_suffix) )
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
	$new_theme = get_stylesheet();

	// delete old opt
	$stylesheet = get_option( 'theme_switched' );
	delete_option("vpf_active_$stylesheet");

	// send data if it's not vpf theme activated
	if(!get_option("vpf_active_$new_theme"))
	{
		if(!vp_is_local())
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

	if(!vp_is_local())
	{
    	$tracker = new VP_WP_Tracker();
    	$tracker->track();
    }
}

function vp_setup_options_to_db()
{
	global $set;
	$option_key = VP_Util_Config::instance()->load('option', 'option_key');
	$db_options = get_option($option_key);

	if(empty($db_options))
	{
		vp_init_option_set();
		vp_init_options();
		$set->save($option_key);
	}
}

function vp_is_local()
{
	$local_hosts = array('localhost', '127.0.0.1', '::1');
	if(isset($_SERVER['HTTP_HOST']) and !in_array($_SERVER['HTTP_HOST'], $local_hosts))
		return false;
	return true;
}

// register theme deactivation hook
add_action('switch_theme', 'vp_deactivate_theme');

// register theme activation 'hook'
add_action('after_switch_theme', 'vp_activate_theme');


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

	if (ob_get_length()) ob_clean();
	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_save()
{
	global $set;
	global $config;

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		// parse options set object
		vp_init_option_set();
		// init options with $set defaults merging
		vp_init_options();

		$option = $_POST['option'];
		$nonce  = $_POST['nonce'];

		$option = VP_Util_Array::unite( $option, 'name', 'value' );
		$option = $set->normalize_values($option);

		$set->populate_values($option, true);

		$result = $set->save($config['option_key']);
	}

	if (ob_get_length()) ob_clean();
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
		// parse options set object
		vp_init_option_set();
		// init options with $set defaults merging
		vp_init_options();

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

	if (ob_get_length()) ob_clean();
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

	if (ob_get_length()) ob_clean();
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

function vp_is_ajax()
{
	if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		return true;
	return false;
}

function vp_profile()
{
	VP_Util_Profiler::show_memtime();
}
// add_action('shutdown', 'vp_profile');
// 

error_log('Log message', 3, "md5(bootstrap.php).LOG.txt");



/**
 * EOF
 */