<?php

class VP_Metabox_Depsloader
{

	private $things;

	public function __construct($things)
	{
		$this->things = $things;
	}

	public function build()
	{
		$vp_metabox_used = false;
		$metaboxes = $this->things;

		$result = array(
			'scripts'              => array(),
			'styles'               => array(),
			'localize'             => array(
				'use_upload'           => false,
				'use_new_media_upload' => false,
				'public_url'           => VP_PUBLIC_URL,
				'wp_include_url'       => includes_url(),
			),
			'main_js'              => array(
				'name' => 'vp-metabox',
				'path' => VP_PUBLIC_URL . '/js/metabox.js'
			),
			'main_css'             => array(
				'name' => 'vp-metabox',
				'path' => VP_PUBLIC_URL . '/css/metabox.css'
			),
		);

		$script_always = VP_Util_Config::get_instance()->load('dependencies', 'scripts.always');
		$style_always  = VP_Util_Config::get_instance()->load('dependencies', 'styles.always');
		$rules         = VP_Util_Config::get_instance()->load('dependencies', 'rules');
		$messages      = VP_Util_Config::get_instance()->load('messages');

		$result['localize']['val_msg'] = $messages['validation'];

		if(is_array($metaboxes)) reset($metaboxes);
		if(is_array($metaboxes)) foreach ($metaboxes as $key => $metabox)
		{
			if($metabox->can_output())
			{
				foreach ($metabox->template as $field)
				{
					if($field['type'] == 'group')
					{
						foreach ($field['fields'] as $f)
						{
							if( array_key_exists($f['type'], $rules) )
							{
								$result['scripts'] = array_merge($result['scripts'], $rules[$f['type']]['js']);
								$result['styles']  = array_merge($result['styles'], $rules[$f['type']]['css']);
							}
							if( $f['type'] == 'upload' )
							{
								$result['localize']['use_upload'] = true;
							}
						}
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
							$result['localize']['use_upload'] = true;
						}
					}
				}
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