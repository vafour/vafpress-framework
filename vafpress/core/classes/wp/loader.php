<?php

class VP_WP_Loader
{

	private $_localize;

	private function setup_localize($use_upload)
	{

		$messages = VP_Util_Config::instance()->load('messages');

		$localize = array(
			'use_upload'           => $use_upload,
			'use_new_media_upload' => false,
			'public_url'           => VP_PUBLIC_URL,
			'wp_include_url'       => includes_url(),
			'nonce'                => wp_create_nonce( 'vafpress' ),
			'val_msg'              => $messages['validation'],
			'impexp_msg'           => $messages['impexp'],
		);
		
		// determine whether to load uploader and which version
		if($localize['use_upload'])
		{
			global $wp_version;
			if (!version_compare($wp_version, '3.5', '<'))
			{
				$localize['use_new_media_upload'] = true;
				wp_enqueue_media();
			}
		}

		// assign localize to be used in further process
		$this->_localize = $localize;		

	}

	public function register($loaders, $hook_suffix = '')
	{

		if(!is_array($loaders))
			$loaders = array($loaders);

		$all_deps     = array();
		$styles_deps  = array();
		$scripts_deps = array();

		foreach ($loaders as $loader)
		{
			// check if we should output
			if($hook_suffix !== '' and !$loader->can_output($hook_suffix))
				break;

			// build dependencies array
			$deps       = $loader->build();
			$all_deps[] = $deps;

			// unite scripts and style
			$styles_deps  = array_merge($styles_deps, $deps['styles']);
			$scripts_deps = array_merge($scripts_deps, $deps['scripts']);
		}


		if(!empty($all_deps))
		{
			// use upload or not, then setup localize
			$use_upload = false;
			foreach ($all_deps as $deps)
			{
				$use_upload = $deps['use_upload'] || $use_upload;
			}
			$this->setup_localize($use_upload);

			// determine whether to load uploader and which version
			if($this->_localize['use_upload'])
			{
				if ($this->_localize['use_new_media_upload'])
				{
					$scripts_deps[] = 'thickbox';
					$styles_deps[]  = 'thickbox';
				}
			}

			// dynamically registering scripts and styles
			$styles = VP_Util_Config::instance()->load('dependencies', 'styles.paths');

			foreach ($scripts_deps as $dep)
				$this->unit_register($dep);

			foreach ($styles as $name => $style) 
				if(in_array($name, $styles_deps) and ! wp_style_is($name, 'registered'))
					wp_register_style($name, $style['path'], $style['deps']);

			// register and add shared-js at the end of dependencies
			$this->unit_register('shared', $scripts_deps);
			$deps['scripts'][] = 'shared';

			foreach ($all_deps as $deps)
			{
				// build main js localize
				foreach ($deps['localize_default'] as $key)
				{
					if(array_key_exists($key, $this->_localize))
					{
						$deps['localize'][$key] = $this->_localize[$key];
					}
				}

				// register, enqueue and localized scripts
				wp_register_script($deps['main_js']['name'], $deps['main_js']['path'], array('shared'), '', true);
				wp_localize_script($deps['main_js']['name'], $deps['localize_name'], $deps['localize']);
				wp_enqueue_script($deps['main_js']['name']);

				// register and enqueue styles
				wp_register_style($deps['main_css']['name'], $deps['main_css']['path'], $styles_deps);
				wp_enqueue_style($deps['main_css']['name']);

			}
		}

	}

	private function unit_register($name, $extra_deps = null)
	{
		global $wp_scripts;

		// dynamically registering scripts
		$scripts     = VP_Util_Config::instance()->load('dependencies', 'scripts.paths');

		$registered  = wp_script_is($name, 'registered');
		$is_older    = false;
		$available   = isset($scripts[$name]);

		if($available)
		{
			$script   = $scripts[$name];
			$override = isset($script['override']) ? $script['override'] : false;
			if($registered)
			{
				$is_older = version_compare($script['ver'], $wp_scripts->registered[$name]->ver) == 1;
			}
			if(!$registered or ($is_older and $override))
			{
				if(!is_null($extra_deps))
				{
					$script['deps'] = array_merge($script['deps'], $extra_deps);
					$script['deps'] = array_unique($script['deps']);
				}
				if(!empty($script['deps']))
				{
					foreach ($script['deps'] as $dep)
					{
						$this->unit_register($dep);
					}
				}
				if($is_older)
				{
					wp_deregister_script($name);
				}

				wp_register_script($name, $script['path'], $script['deps'], $script['ver'], true);

				if(isset($script['localize']))
				{
					$localize = array();
					foreach ($script['localize']['keys'] as $key)
					{
						if(array_key_exists($key, $this->_localize))
						{
							$localize[$key] = $this->_localize[$key];
						}
					}
					wp_localize_script($name, $script['localize']['name'], $localize);
				}
			}
		}
	}

}

/**
 * EOF
 */