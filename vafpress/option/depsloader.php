<?php

class VP_Option_Depsloader
{

	private $things;

	public function __construct($things)
	{
		$this->things = $things;
	}

	public function build()
	{

		$set = $this->things;

		$result = array(
			'scripts'              => array(),
			'styles'               => array(),
			'localize'             => array(
				'use_upload'           => false,
				'use_new_media_upload' => false,
				'public_url'           => VP_PUBLIC_URL,
				'nonce'                => wp_create_nonce( 'vafpress' ),
			),
			'main_js'              => array(
				'name' => 'vp-option-js',
				'path' => VP_PUBLIC_URL . '/js/option.js'
			),
			'main_css'             => array(
				'name' => 'vp-option-css',
				'path' => VP_PUBLIC_URL . '/css/option.css'
			),
		);

		$result['scripts'] = VP_Util_Config::get_instance()->load('dependencies', 'scripts.always');
		$result['styles']  = VP_Util_Config::get_instance()->load('dependencies', 'styles.always');

		$scripts     = VP_Util_Config::get_instance()->load('dependencies', 'scripts.paths');
		$styles      = VP_Util_Config::get_instance()->load('dependencies', 'styles.paths');
		$rules       = VP_Util_Config::get_instance()->load('dependencies', 'rules');

		$fields = $set->get_fields();
		foreach ($fields as $field)
		{
			$type = VP_Util_Text::field_type_from_class(get_class($field));
			if( array_key_exists($type, $rules) )
			{
				$result['scripts'] = array_merge($result['scripts'], $rules[$type]['js']);
				$result['styles']  = array_merge($result['styles'], $rules[$type]['css']);
			}
			// check if using upload button
			if( $type == 'upload' )
			{
				$result['localize']['use_upload'] = true;
			}
		}
		$result['scripts'] = array_unique($result['scripts']);
		$result['styles']  = array_unique($result['styles']);

		// build localize
		$messages = VP_Util_Config::get_instance()->load('messages');
		$result['vp_public_url']          = VP_PUBLIC_URL;
		$result['localize']['val_msg']    = $messages['validation'];
		$result['localize']['impexp_msg'] = $messages['impexp'];

		return $result;
	}

	public function can_output($hook_suffix = '')
	{
		// if not in option page, don't load
		$menu_page_slug = VP_Util_Config::get_instance()->load('option/main', 'menu_page_slug');
		if( $hook_suffix == ('appearance_page_' . $menu_page_slug) )
			return true;
		return false;
	}

}