<?php

/**
 * Theme Options Bootstrapping
 */

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
	global $vp_set, $vp_opt;
	$screen = get_current_screen();

	// if we are at option page
	if(vp_is_option_page($screen->id))
	{
		// parse options set object
		vp_init_option_set();

		// init wp editor
		vp_init_wpeditor();

		// init options with $vp_set defaults merging
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

function vp_init_wpeditor()
{
	global $vp_set;
	$types = $vp_set->get_field_types();
	if(in_array('wpeditor', $types))
	{
		echo '<div style="display: none">';
		add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
		wp_editor( '', 'vp_dummy_editor' );
		echo '</div>';
	}
}

function vp_init_option_set()
{
	global $vp_set;

	// Parse the option
	try{
		$option_path = VP_FileSystem::instance()->resolve_path('builder', 'option/option');
		$options     = include($option_path);
	} catch (Exception $e){
		echo $e->getMessage();
	}
	$parser = new VP_Option_Parser();
	$vp_set	= $parser->parse_array_options($options);

	// setup utility menu
	$util_menu = new VP_Option_Control_Group_Menu();
	$util_menu->set_title(__('Utility', 'vp_textdomain'));
	$util_menu->set_name('utility');
	$util_menu->set_icon('font-awesome:icon-wrench');

	// Add tracking option
	$et_section = new VP_Option_Control_Group_Section();
	$et_section->set_name('et_section');

	$help_note = new VP_Control_Field_NoteBox();
	$help_note->set_label(__('Help Us!', 'vp_textdomain'));
	$help_note->set_status('info');
	$help_note->set_description(__('Send analytic data to Vafpress to help us improve the framework better, we\'re not collecting private data, and this won\'t slow down your site :)', 'vp_textdomain'));

	$enable_tracker = new VP_Control_Field_Toggle();
	$enable_tracker->set_name('enable_tracking');
	$enable_tracker->set_label(__('Enable Tracking', 'vp_textdomain'));

	$et_section->add_field($help_note);
	$et_section->add_field($enable_tracker);

	$util_menu->add_control($et_section);

	// Add Import and Export Option Functionality
	if(VP_Util_Config::instance()->load('option', 'impexp'))
	{
		$ie_section = new VP_Option_Control_Group_Section();
		$ie_section->set_name('ie_section');
		$ie_section->set_title(__('Import / Export Settings', 'vp_textdomain'));
		$ie_field   = new VP_Option_Control_Field_ImpExp();
		$ie_section->add_field($ie_field);
		$util_menu->add_control($ie_section);
	}
	$vp_set->add_menu($util_menu);
}

////////////////////////////////////////////
// Load Options to be used in option page //
////////////////////////////////////////////
/**
 * @todo load default values, and then check on db, if not available then save to the db
 * @todo load option from db and expose them to be used on theme
 */
function vp_init_options()
{
	global $vp_config, $vp_set, $vp_opt;

	// try load option from DB
	$db_options = get_option($vp_config['option_key']);
	$default    = $vp_set->get_defaults();
	if (!empty($db_options))
	{
		// unify, preserve option from DB but appends anything new from default
		$vp_opt = $db_options;
		$vp_opt = $vp_opt + $default;
	}
	else
	{
		$vp_opt = $vp_set->get_defaults();
		update_option($vp_config['option_key'], $vp_opt);
	}

	// If dev mode, always use default, no db interaction
	if($vp_config['dev_mode'])
		$vp_opt = $vp_set->get_defaults();

	// populate option to fields' values
	$vp_set->populate_values($vp_opt);

	// process binding
	$vp_set->process_binding();

	// process dependencies
	$vp_set->process_dependencies();
}

function vp_init_options_db()
{
	global $vp_config, $vp_opt;

	// try load option from DB
	$db_options = get_option($vp_config['option_key']);
	if (!empty($db_options))
	{
		$vp_opt = $db_options;
	}
}

// helper function to obtain option value
function vp_option($key)
{
	global $vp_opt;
	if(array_key_exists($key, $vp_opt))
	{
		$vp_opt[$key] = apply_filters( 'vp_option_get_value', $vp_opt[$key], $key );
		return $vp_opt[$key];
	}
	return null;
}


///////////////////////////////
// Theme Menu and Page Setup //
///////////////////////////////
function vafpress_theme_menu()
{
	global $vp_set;
	global $vp_config;
	add_theme_page(
		$vp_config['browser_page_title'], // The title to be displayed in the browser window for this page.
		$vp_config['menu_page_label'],    // The text to be displayed for this menu item
		$vp_config['role'],               // Which type of users can see this menu item
		$vp_config['menu_page_slug'],     // The unique ID - that is, the slug - for this menu item
		'vafpress_theme_display'          // The name of the function to call when rendering the page for this menu
	);
}

function vafpress_theme_display()
{
	// render the page
	global $vp_set;
	echo $vp_set->render();
}

function vp_load_scripts_and_styles()
{
	global $vp_set;
	global $opt_deps_loader;

	// load scripts and styles dependencies
	$opt_loader      = new VP_WP_Loader();
	$opt_deps_loader = new VP_Option_Depsloader($vp_set);
	$opt_loader->register($opt_deps_loader);
}

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

// development mode notice
add_action('admin_notices', 'vp_opt_notice_devmode');

function vp_setup_options_to_db()
{
	global $vp_set;
	$option_key = VP_Util_Config::instance()->load('option', 'option_key');
	$db_options = get_option($option_key);

	if(empty($db_options))
	{
		vp_init_option_set();
		vp_init_options();

		$opt = $vp_set->get_values();
		// before db options db action hook
		do_action('vp_option_before_db_init', $opt);
		// save to db
		$result = $vp_set->save($option_key);
		// after db options db action hook
		do_action('vp_option_after_db_init', $opt, $result['status'], $option_key);
	}
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

	if (ob_get_length()) ob_clean();
	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_save()
{
	global $vp_set, $vp_config;

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		// parse options set object
		vp_init_option_set();
		// init options with $vp_set defaults merging
		vp_init_options();

		$option = $_POST['option'];
		$nonce  = $_POST['nonce'];

		$option = VP_Util_Array::unite( $option, 'name', 'value' );
		$option = $vp_set->normalize_values($option);

		$vp_set->populate_values($option, true);

		// get back options from set
		$opt = $vp_set->get_values();

		// before ajax save action hook
		do_action('vp_option_before_ajax_save', $opt);

		// do saving
		$result = $vp_set->save($vp_config['option_key']);

		// re-init $opt
		vp_init_options_db();

		// after ajax save action hook
		do_action('vp_option_after_ajax_save', $opt, $result['status'], $vp_config['option_key']);
	}

	if (ob_get_length()) ob_clean();
	header('Content-type: application/json');
	echo json_encode($result);
	die();
}

function vp_ajax_import_option()
{
	global $vp_set, $vp_config;

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		// parse options set object
		vp_init_option_set();
		// init options with $vp_set defaults merging
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
				$vp_set->populate_values($option, true);
				$result = $vp_set->save($vp_config['option_key']);
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
	global $vp_config;

	$result = vp_verify_nonce();
	
	if($result['status'])
	{
		$db_options = get_option($vp_config['option_key']);
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

/**
 * EOF
 */