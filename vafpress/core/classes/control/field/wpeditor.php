<?php

class VP_Control_Field_WPEditor extends VP_Control_Field
{

	public function __construct()
	{
		parent::__construct();
	}

	public static function withArray($arr = array())
	{
		$instance = new self();
		$instance->_basic_make($arr);
		return $instance;
	}

	public function render($is_compact = false)
	{
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/wpeditor', $this->get_data());
	}

}

/**
 * EOF
 */