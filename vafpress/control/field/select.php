<?php

class VP_Control_Field_Select extends VP_Control_FieldMulti
{

	public function __construct()
	{
		$this->_value = array();
	}

	public static function withArray($arr)
	{
		$instance = new self();
		$instance->_basic_make($arr);

		// Turn default array to single value
		$instance->set_default(VP_Util_Array::first($instance->get_default()));
		
		return $instance;
	}

	public function render()
	{
		$this->_setup_data();
		return VP_View::get_instance()->load('control/select', $this->get_data());
	}

}

/**
 * EOF
 */