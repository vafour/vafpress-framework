<?php

if( defined('VP_VERSION') )
	return;

//////////////////////////
// Include Constants    //
//////////////////////////
require_once 'constant.php';

//////////////////////////
// Include Autoloader   //
//////////////////////////
require_once 'autoload.php';

//////////////////////////
// Load Languages       //
//////////////////////////
load_theme_textdomain('vp_textdomain', VP_DIR . '/lang');

//////////////////////////
// Setup FileSystem     //
//////////////////////////
$vpfs = VP_FileSystem::instance();
$vpfs->add_directories('views'   , VP_VIEWS_DIR);
$vpfs->add_directories('config'  , VP_CONFIG_DIR);
$vpfs->add_directories('data'    , VP_DATA_DIR);
$vpfs->add_directories('includes', VP_INCLUDE_DIR);

//////////////////////////
// Include Data Source  //
//////////////////////////
foreach (glob(VP_DATA_DIR . "/*.php") as $datasource)
{
	require_once($datasource);
}

//////////////////////////
// TGMPA Unsetting      //
//////////////////////////
add_action('after_setup_theme', 'vp_tgm_ac_check');

if( !function_exists('vp_tgm_ac_check') )
{
	function vp_tgm_ac_check()
	{
		add_action('tgmpa_register', 'vp_tgm_ac_vafpress_check');	
	}
}

if( !function_exists('vp_tgm_ac_vafpress_check') )
{
	function vp_tgm_ac_vafpress_check()
	{
		if( defined('VP_VERSION') and class_exists('TGM_Plugin_Activation') )
		{
			foreach (TGM_Plugin_Activation::$instance->plugins as $key => &$plugin)
			{
				if( $plugin['name'] === 'Vafpress Framework Plugin' )
				{
					unset(TGM_Plugin_Activation::$instance->plugins[$key]);
				}
			}
		}
	}
}

//////////////////////////
// Ajax Definition      //
//////////////////////////
add_action('wp_ajax_vp_ajax_wrapper', 'vp_ajax_wrapper');

if( !function_exists('vp_ajax_wrapper') )
{
	function vp_ajax_wrapper()
	{
		$function = $_POST['func'];
		$params   = $_POST['params'];

		if( VP_Security::instance()->is_function_whitelisted($function) )
		{
			if(!is_array($params))
				$params = array($params);

			try {
				$result['data']    = call_user_func_array($function, $params);
				$result['status']  = true;
				$result['message'] = __("Successful", 'vp_textdomain');
			} catch (Exception $e) {
				$result['data']    = '';
				$result['status']  = false;
				$result['message'] = $e->getMessage();		
			}
		}
		else
		{
			$result['data']    = '';
			$result['status']  = false;
			$result['message'] = __("Unauthorized function", 'vp_textdomain');		
		}

		if (ob_get_length()) ob_clean();
		header('Content-type: application/json');
		echo json_encode($result);
		die();
	}
}

/////////////////////////////////
// Pool and Dependencies Init  //
/////////////////////////////////
add_action( 'init'                 , 'vp_metabox_enqueue' );
add_action( 'current_screen'       , 'vp_sg_enqueue' );
add_action( 'admin_enqueue_scripts', 'vp_enqueue_scripts' );
add_action( 'current_screen'       , 'vp_sg_init_buttons' );
add_filter( 'clean_url'            , 'vp_ace_script_attributes', 10, 1 );

if( !function_exists('vp_ace_script_attributes') )
{
	function vp_ace_script_attributes( $url )
	{
		if ( FALSE === strpos( $url, 'ace.js' ) )
			return $url;

		return "$url' charset='utf8";
	}
}

if( !function_exists('vp_metabox_enqueue') )
{
	function vp_metabox_enqueue()
	{
		if( VP_WP_Admin::is_post_or_page() and VP_Metabox::pool_can_output() )
		{
			$loader = VP_WP_Loader::instance();
			$loader->add_main_js( 'vp-metabox' );
			$loader->add_main_css( 'vp-metabox' );
		}
	}
}

if( !function_exists('vp_sg_enqueue') )
{
	function vp_sg_enqueue()
	{
		if( VP_ShortcodeGenerator::pool_can_output() )
		{
			// enqueue dummy js
			$localize = VP_ShortcodeGenerator::build_localize();
			wp_register_script( 'vp-sg-dummy', VP_PUBLIC_URL . '/js/dummy.js', array(), '', false );
			wp_localize_script( 'vp-sg-dummy', 'vp_sg', $localize );
			wp_enqueue_script( 'vp-sg-dummy' );

			$loader = VP_WP_Loader::instance();
			$loader->add_main_js( 'vp-shortcode' );
			$loader->add_main_css( 'vp-shortcode' );
		}
	}
}

add_action('admin_footer', 'vp_post_dummy_editor');

if( !function_exists('vp_post_dummy_editor') )
{
	function vp_post_dummy_editor()
	{
		/**
		 * If we're in post edit page, and the post type doesn't support `editor`
		 * we need to echo out a dummy editor to load all necessary js and css
		 * to be used in our own called wp editor.
		 */
		$loader = VP_WP_Loader::instance();
		$types  = $loader->get_types();
		$dummy  = false;

		if( VP_WP_Admin::is_post_or_page() )
		{
			$types = array_unique( array_merge( $types['metabox'], $types['shortcodegenerator'] ) );
			if( in_array('wpeditor', $types ) )
			{
				if( !VP_ShortcodeGenerator::pool_supports_editor() and !VP_Metabox::pool_supports_editor() )
					$dummy = true;
			}
		}
		else
		{
			$types = $types['option'];
			if( in_array('wpeditor', $types ) )
				$dummy = true;
		}

		if( $dummy )
		{
			echo '<div style="display: none">';
			add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
			wp_editor ( '', 'vp_dummy_editor' );
			echo '</div>';		
		}
	}
}

if( !function_exists('vp_sg_init_buttons') )
{
	function vp_sg_init_buttons()
	{
		if( VP_ShortcodeGenerator::pool_can_output() )
		{
			VP_ShortcodeGenerator::init_buttons();
		}
	}
}

if( !function_exists('vp_enqueue_scripts') )
{
	function vp_enqueue_scripts()
	{
		$loader = VP_WP_Loader::instance();
		$loader->build();
	}
}

/**
 * Easy way to get metabox values using dot notation
 * example:
 * 
 * vp_metabox('meta_name.field_name')
 * vp_metabox('meta_name.group_name')
 * vp_metabox('meta_name.group_name.0.field_name')
 * 
 */

if( !function_exists('vp_metabox') )
{
	function vp_metabox($key, $default = null, $post_id = null)
	{
		global $post;

		$vp_metaboxes = VP_Metabox::get_pool();

		if(!is_null($post_id))
		{
			$the_post = get_post($post_id);
			if ( empty($the_post) ) $post_id = null;
		}
			
		if(is_null($post) and is_null($post_id))
			return $default;

		$keys = explode('.', $key);
		$temp = NULL;

		foreach ($keys as $idx => $key)
		{
			if($idx == 0)
			{
				if(array_key_exists($key, $vp_metaboxes))
				{
					$temp = $vp_metaboxes[$key];
					if(!is_null($post_id))
						$temp->the_meta($post_id);
					else
						$temp->the_meta();
				}
				else
				{
					return $default;
				}
			}
			else
			{
				if(is_object($temp) and get_class($temp) === 'VP_Metabox')
				{
					$temp = $temp->get_the_value($key);
				}
				else
				{
					if(is_array($temp) and array_key_exists($key, $temp))
					{
						$temp = $temp[$key];
					}
					else
					{
						return $default;
					}
				}
			}
		}
		return $temp;
	}
}

/**
 * Easy way to get option values using dot notation
 * example:
 * 
 * vp_option('option_key.field_name')
 * 
 */

if( !function_exists('vp_option') )
{
	function vp_option($key, $default = null)
	{
		$vp_options = VP_Option::get_pool();

		if(empty($vp_options))
			return $default;

		$keys = explode('.', $key);
		$temp = NULL;

		foreach ($keys as $idx => $key)
		{
			if($idx == 0)
			{
				if(array_key_exists($key, $vp_options))
				{
					$temp = $vp_options[$key];
					$temp = $temp->get_options();
				}
				else
				{
					return $default;
				}
			}
			else
			{
				if(is_array($temp) and array_key_exists($key, $temp))
				{
					$temp = $temp[$key];
				}
				else
				{
					return $default;
				}
			}
		}
		return $temp;
	}
}

/**
 * EOF
 */