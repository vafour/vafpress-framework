<?php

class VP_Control_Field_MultiSelect extends VP_Control_FieldMulti implements VP_MultiSelectable
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
		
		return $instance;
	}

	public function render()
	{
		$this->_setup_data();
		return VP_View::instance()->load('control/multiselect', $this->get_data());
	}

}

/**
 * EOF
 */