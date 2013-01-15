<?php

// include original WPAlchemy class to be extended later on.
include_once VP_INCLUDE_DIR . '/wpalchemy/MetaBox.php';

/**
 * global variable to store and expose all metaboxes objects
 */
global $vp_metaboxes;

// load all files from metaboxes
$metas = array();
foreach (glob(VP_CONFIG_DIR . "/metabox/instances/*.php") as $filename)
{
	$metas[] = include($filename);
}
if(empty($metas))
{
	foreach (glob(VP_CONFIG_DIR . "/metabox/instances/*.php.sample") as $filename)
	{
		$metas[] = include($filename);
	}
}

// if there is metaboxes
if(!empty($metas))
{	
	// development mode notice
	if(VP_Util_Config::get_instance()->load('metabox/main', 'dev_mode'))
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
	$vp_metaboxes[ $meta['id'] ] = new VP_MetaBox_Alchemy(array(
		'id'       => $meta['id'],
		'title'    => $meta['title'],
		'types'    => $meta['types'],
		'template' => $meta
	));
}


/**
 * @todo error checking
 * 
 * easy way to get metabox values using dot notation
 * example:
 * vp_metabox('meta_name.field_name')
 * vp_metabox('meta_name.group_name')
 * vp_metabox('meta_name.group_name.0.field_name')
 */
function vp_metabox($key)
{
	global $vp_metaboxes;
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
				throw new Exception("Metabox Undefined", 1);
			}
		}
		else
		{
			if( is_object($temp) and get_class($temp) === 'VP_MetaBox_Alchemy' )
			{
				$temp = $temp->get_the_value($key);
			}
			else
			{
				if(array_key_exists($key, $temp))
				{
					$temp = $temp[$key];
				}
				else
				{
					throw new Exception("No Value", 1);
				}
			}
		}
	}
	return $temp;
}

// load metaboxes scripts and styles dependencies
$mb_loader      = new VP_WP_Loader();
$mb_deps_loader = new VP_Metabox_Depsloader($vp_metaboxes);
$mb_loader->register($mb_deps_loader);

/**
 * EOF
 */