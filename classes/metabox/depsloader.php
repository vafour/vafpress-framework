<?php

class VP_Metabox_Depsloader
{

	/**
	 * ARRAY OF VP_METABOX_ALCHEMY OBJECT
	 * @var [type]
	 */
	private $things;

	public function __construct($things)
	{
		$this->things = array($things);
	}

	public function build()
	{
		$vp_metabox_used = false;
		$metaboxes = $this->things;

		$result = array(
			'scripts'              => array(),
			'styles'               => array(),
			'localize_name'        => 'vp_mb',
			'localize_default'     => array(),
			'localize'             => array(),
			'use_upload'           => false,
			'use_new_media_upload' => false,
			'main_js'              => array(
				'name' => 'vp-metabox',
				'path' => VP_PUBLIC_URL . '/js/metabox.min.js'
			),
			'main_css'             => array(
				'name' => 'vp-metabox',
				'path' => VP_PUBLIC_URL . '/css/metabox.min.css'
			),
		);

		$script_always = VP_Util_Config::instance()->load('dependencies', 'scripts.always');
		$style_always  = VP_Util_Config::instance()->load('dependencies', 'styles.always');
		$messages      = VP_Util_Config::instance()->load('messages');

		$result['localize']['val_msg'] = $messages['validation'];

		if(is_array($metaboxes)) reset($metaboxes);
		if(is_array($metaboxes)) foreach ($metaboxes as $key => $metabox)
		{
			if($metabox->can_output())
			{
				if(!function_exists('inner_build'))
				{
					function inner_build($fields, &$result)
					{
						$rules = VP_Util_Config::instance()->load('dependencies', 'rules');
						foreach ($fields as $field)
						{
							if($field['type'] == 'group')
							{
								inner_build($field['fields'], $result);
							}
							else
							{
								if( array_key_exists($field['type'], $rules) )
								{
									$result['scripts'] = array_merge($result['scripts'], $rules[$field['type']]['js']);
									$result['styles']  = array_merge($result['styles'], $rules[$field['type']]['css']);
								}
								if( $field['type'] == 'upload' )
								{
									$result['use_upload'] = true;
								}
							}
						}
					}
				}
				inner_build($metabox->template, $result);
				// at least one metabox used, then let's load
				$vp_metabox_used = true;
			}

			if($vp_metabox_used)
			{
				$result['scripts'] = array_merge($result['scripts'], $script_always);
				$result['styles']  = array_merge($result['styles'], $style_always);
			}
			$result['scripts'] = array_unique($result['scripts']);
			$result['styles']  = array_unique($result['styles']);		
		}
		return $result;
	}

	public function can_output($hook_suffix = '')
	{
		if ( WPAlchemy_MetaBox::_is_post() or WPAlchemy_MetaBox::_is_page() )
			return true;
		return false;
	}

}