<?php

class VP_Option_Depsloader
{

	/**
	 * The VP_Option_Set object!
	 */
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
			'localize_name'        => 'vp_opt',
			'localize_default'     => array(
				'nonce', 'impexp_msg'
			),
			'localize'             => array(
				'name' => 'vpt_option'
			),
			'use_upload'           => false,
			'use_new_media_upload' => false,
			'main_js'  => array(
				'name' => 'vp-option',
				'path' => VP_PUBLIC_URL . '/js/option.min.js',
			),
			'main_css' => array(
				'name' => 'vp-option',
				'path' => VP_PUBLIC_URL . '/css/option.min.css'
			),
		);

		$result['scripts'] = VP_Util_Config::instance()->load('dependencies', 'scripts.always');
		$result['styles']  = VP_Util_Config::instance()->load('dependencies', 'styles.always');

		$scripts = VP_Util_Config::instance()->load('dependencies', 'scripts.paths');
		$styles  = VP_Util_Config::instance()->load('dependencies', 'styles.paths');
		$rules   = VP_Util_Config::instance()->load('dependencies', 'rules');

		$fields  = $set->get_fields();
		foreach ($fields as $field)
		{
			$type = VP_Util_Reflection::field_type_from_class(get_class($field));
			if( array_key_exists($type, $rules) )
			{
				$result['scripts'] = array_merge($result['scripts'], $rules[$type]['js']);
				$result['styles']  = array_merge($result['styles'], $rules[$type]['css']);
			}
			// check if using upload button
			if( $type == 'upload' )
			{
				$result['use_upload'] = true;
			}
		}
		$result['scripts'] = array_unique($result['scripts']);
		$result['styles']  = array_unique($result['styles']);

		return $result;
	}

	public function can_output($hook_suffix = '')
	{
		// if not in option page, don't load
		$menu_page_slug = VP_Util_Config::instance()->load('option', 'menu_page_slug');
		if( $hook_suffix == ('appearance_page_' . $menu_page_slug) )
			return true;
		return false;
	}

}