<?php

class VP_Control_Field_Toggle extends VP_Control_Field
{

	public function __construct(){}

	public static function withArray($arr)
	{
		$instance = new self();
		$instance->_basic_make($arr);
		return $instance;
	}

	public function render()
	{
		$this->_setup_data();
		return VP_View::get_instance()->load('control/toggle', $this->get_data());
	}

}

/**
 * EOF
 */