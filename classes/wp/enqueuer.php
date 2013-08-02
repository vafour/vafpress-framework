<?php

/**
 * For singleton accessor, use VP_WP_MassEnqueuer class instead.
 */
class VP_WP_Enqueuer
{

	private $_loaders = array();

	private $_id;

	public function __construct()
	{
		$this->_id = spl_object_hash($this);
		$loader    = new VP_WP_Loader();
		add_action('vp_loader_register_' . $this->_id, array($loader, 'register'), 10, 2);
	}

	public function add_loader($loader)
	{
		$this->_loaders[] = $loader;
	}

	public function register()
	{
		add_action('admin_enqueue_scripts', array($this, 'register_caller'));
	}

	public function register_caller($hook_suffix)
	{
		do_action('vp_loader_register_' . $this->_id, $this->_loaders, $hook_suffix);
	}

}