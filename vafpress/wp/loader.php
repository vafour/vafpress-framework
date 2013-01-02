<?php

class VP_WP_Loader
{

	private $args;

	private $id;

	public function __construct()
	{
		$args     = null;
		$this->id = spl_object_hash($this);
		add_action('vp_loader_register_' . $this->id, array($this, 'register_real'), 10, 2);
	}

	public function register($deps)
	{
		$this->args = $deps;
		add_action('admin_enqueue_scripts', array($this, 'register_caller'));
	}

	public function register_caller($hook_suffix)
	{
		do_action('vp_loader_register_' . $this->id, $this->args, $hook_suffix);
	}

	public function register_real($loader, $hook_suffix)
	{
		// check if we should oupout
		if(!$loader->can_output($hook_suffix))
			return;

		// build dependencies array
		$deps = $loader->build();

		// determine whether to load uploader and which version
		if($deps['localize']['use_upload'])
		{
			wp_enqueue_media();

			global $wp_version;
			if (version_compare($wp_version, '3.5', '<')) {
				// version is under 3.5
				$deps['scripts'][] = 'thickbox';
				$deps['styles'][]  = 'thickbox';
			}
			else
			{
				$deps['localize']['use_new_media_upload'] = true;
			}
		}

		// dynamically registering scripts
		$scripts     = VP_Util_Config::get_instance()->load('dependencies', 'scripts.paths');
		$styles      = VP_Util_Config::get_instance()->load('dependencies', 'styles.paths');

		foreach ($scripts as $script) 
		{
			if(in_array($script['name'], $deps['scripts']) and ! wp_script_is($script['name'], 'registered'))
			{
				wp_register_script($script['name'], $script['path'], $script['deps'], '', true);
			}
		}

		foreach ($styles as $style) 
		{
			if(in_array($style['name'], $deps['styles']) and ! wp_style_is($style['name'], 'registered'))
			{
				wp_register_style($style['name'], $style['path'], $style['deps']);
			}
		}

		// register, enqueue and localized scripts
		wp_register_script($deps['main_js']['name'], $deps['main_js']['path'], $deps['scripts'], '', true);
		wp_enqueue_script($deps['main_js']['name']);
		wp_localize_script($deps['main_js']['name'], 'vp', $deps['localize']);

		// register and enqueue styles
		wp_register_style($deps['main_css']['name'], $deps['main_css']['path'], $deps['styles']);
		wp_enqueue_style($deps['main_css']['name']);

	}

}