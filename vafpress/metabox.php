<?php

// include original WPAlchemy class to be extended later on.
include_once VP_INCLUDE_DIR . '/wpalchemy/MetaBox.php';

/**
 * global variable to store and expose all metaboxes objects
 */
global $vp_metaboxes;

// load all files from metaboxes
foreach (glob(VP_CONFIG_DIR . "/metaboxes/*.php") as $filename)
{
	$metas[] = include($filename);
}

// instantiate metaboxes object
foreach ($metas as $meta)
{
	$vp_metaboxes[ $meta['name'] ] = new VP_MetaBox_Alchemy(array(
		'id'       => $meta['name'],
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
 * vp_mb_get('meta_name.field_name')
 * vp_mb_get('meta_name.group_name')
 * vp_mb_get('meta_name.group_name.0.field_name')
 */
function vp_mb_get($key)
{
	global $vp_metaboxes;
	$keys = explode('.', $key);
	$temp = NULL;
	foreach ($keys as $idx => $key)
	{
		if($idx == 0)
		{
			$temp = $vp_metaboxes[$key];
			$temp->the_meta();
		}
		else
		{
			if( is_object($temp) and get_class($temp) == 'VP_MetaBox_Alchemy' )
				$temp = $temp->get_the_value($key);
			else
				$temp = $temp[$key];
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