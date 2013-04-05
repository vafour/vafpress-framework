<?php

class VP_WP_Loader
{

	private $localize;

	public function register($loader, $hook_suffix = '')
	{

		// check if we should oupout
		if($hook_suffix !== '' and !$loader->can_output($hook_suffix))
			return;

		// build dependencies array
		$deps = $loader->build();

		// determine whether to load uploader and which version
		if($deps['localize']['use_upload'])
		{
			global $wp_version;
			if (version_compare($wp_version, '3.5', '<')) {
				// version is under 3.5
				$deps['scripts'][] = 'thickbox';
				$deps['styles'][]  = 'thickbox';
			}
			else
			{
				$deps['localize']['use_new_media_upload'] = true;
				wp_enqueue_media();
			}
		}

		// assign localize to be used in further process
		$this->localize = $deps['localize'];

		// dynamically registering scripts and styles
		$styles = VP_Util_Config::instance()->load('dependencies', 'styles.paths');

		global $wp_scripts;

		foreach ($deps['scripts'] as $dep)
		{
			$this->unit_register($dep);
		}

		foreach ($styles as $name => $style) 
		{
			if(in_array($name, $deps['styles']) and ! wp_style_is($name, 'registered'))
			{
				wp_register_style($name, $style['path'], $style['deps']);
			}
		}

		// register and add shared-js at the end of dependencies
		$this->unit_register('shared');
		$deps['scripts'][] = 'shared';

		// register, enqueue and localized scripts
		wp_register_script($deps['main_js']['name'], $deps['main_js']['path'], $deps['scripts'], '', true);
		wp_localize_script($deps['main_js']['name'], 'vp_wp', $deps['localize']);
		wp_enqueue_script($deps['main_js']['name']);

		// register and enqueue styles
		wp_register_style($deps['main_css']['name'], $deps['main_css']['path'], $deps['styles']);
		wp_enqueue_style($deps['main_css']['name']);
	}

	private function unit_register($name)
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
						if(array_key_exists($key, $this->localize))
						{
							$localize[$key] = $this->localize[$key];
						}
					}
					wp_localize_script($name, $script['localize']['name'], $localize);
				}
			}
		}
	}

}