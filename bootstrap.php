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

// ajax definition
add_action('wp_ajax_vp_ajax_wrapper', 'vp_ajax_wrapper');

if( !function_exists('vp_ajax_wrapper') )
{
	function vp_ajax_wrapper()
	{
		$function = $_POST['func'];
		$params   = $_POST['params'];

		if(!is_array($params))
			$params = array($params);

		try {
			$result['data']    = call_user_func_array($function, $params);
			$result['status']  = true;
			$result['message'] = __("Success", 'vp_textdomain');
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
}

add_action( 'admin_enqueue_scripts', 'vp_metabox_enqueue' );
add_action( 'admin_enqueue_scripts', 'vp_sg_enqueue' );
add_action( 'current_screen'       , 'vp_sg_init_buttons' );

if( !function_exists('vp_metabox_enqueue') )
{
	function vp_metabox_enqueue()
	{
		if( VP_WP_Admin::is_post_or_page() and VP_Metabox::pool_can_output() )
		{
			$loader = VP_WP_Loader::instance();
			$loader->add_main_js( 'vp-metabox' );
			$loader->add_main_css( 'vp-metabox' );
			$loader->build();
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
			$loader->build();
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