<?php

/**
 * Metabox bootstrapping
 */

/////////////////////////////////////////
// Include original WPAlchemy Class    //
/////////////////////////////////////////
if(!class_exists('WPAlchemy_MetaBox'))
{
	require_once VP_FileSystem::instance()->resolve_path('includes', 'wpalchemy/MetaBox');
}

/**
 * global variable to store and expose all metaboxes objects
 */
global $vp_metaboxes;

/////////////////////////////////////////
// Load all Metabox definition files   //
/////////////////////////////////////////
$metas = array();
$dir   = VP_FileSystem::instance()->get_first_non_empty_dir('builder', 'metabox');
foreach (glob($dir . DIRECTORY_SEPARATOR . "*.php") as $filename)
{
	$metas[] = include_once($filename);
}

// if there is metaboxes
if(!empty($metas))
{	
	// development mode notice
	if(VP_Util_Config::instance()->load('metabox', 'dev_mode'))
	{
		if ( WPAlchemy_MetaBox::_is_post() or WPAlchemy_MetaBox::_is_page() )
		{
			add_action('admin_notices', 'vp_mb_notice_devmode');
		}
	}
}

function vp_mb_notice_devmode()
{
    VP_WP_Util::admin_notice(__("[Vafpress Framework] Metabox Development Mode is Active, value won't be saved into database.", 'vp_textdomain'), false);
}

// instantiate metaboxes object
foreach ($metas as $meta)
{
	$vp_metaboxes[ $meta['id'] ] = new VP_MetaBox_Alchemy($meta);
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
function vp_metabox($key)
{
	global $vp_metaboxes, $post;

	if(is_null($post))
		return null;

	$keys = explode('.', $key);
	$temp = NULL;
	foreach ($keys as $idx => $key)
	{
		if($idx == 0)
		{
			if(array_key_exists($key, $vp_metaboxes))
			{
				$temp = $vp_metaboxes[$key];
				$temp->the_meta();
			}
			else
			{
				return null;
			}
		}
		else
		{
			if(is_object($temp) and get_class($temp) === 'VP_MetaBox_Alchemy')
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
					return null;
				}
			}
		}
	}
	return $temp;
}

// load metaboxes scripts and styles dependencies
$mb_deps_loader = new VP_Metabox_Depsloader($vp_metaboxes);
VP_WP_MassEnqueuer::instance()->add_loader($mb_deps_loader);

/* 
 * the_content clone filter
 */
add_filter( 'meta_content', 'wptexturize'        );
add_filter( 'meta_content', 'convert_smilies'    );
add_filter( 'meta_content', 'convert_chars'      );
add_filter( 'meta_content', 'wpautop'            );
add_filter( 'meta_content', 'shortcode_unautop'  );
add_filter( 'meta_content', 'prepend_attachment' );

/**
 * EOF
 */