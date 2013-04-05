<?php

class VP_WP_Enqueuer
{

	private $args;

	private $id;

	private $localize;

	public function __construct()
	{
		$args     = null;
		$this->id = spl_object_hash($this);
		$loader = new VP_WP_Loader();
		add_action('vp_loader_register_' . $this->id, array($loader, 'register'), 10, 2);
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
}