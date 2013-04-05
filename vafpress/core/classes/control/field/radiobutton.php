<?php

class VP_Control_Field_RadioButton extends VP_Control_FieldMulti
{

	public function __construct()
	{
		parent::__construct();
		$this->_value = array();
	}

	public static function withArray($arr)
	{
		$instance = new self();
		$instance->_basic_make($arr);
		$instance->add_container_extra_classes('vp-checked-field');
		
		// Turn default array to single value
		$instance->set_default(VP_Util_Array::first($instance->get_default()));
		
		return $instance;
	}

	public function render()
	{
		$this->_setup_data();
		return VP_View::instance()->load('control/radiobutton', $this->get_data());
	}

}

/**
 * EOF
 */