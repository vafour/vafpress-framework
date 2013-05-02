<?php

class VP_Control_Field_CheckBox extends VP_Control_FieldMulti implements VP_MultiSelectable
{

	public function __construct()
	{
		parent::__construct();
		$this->_value = array();
		$this->add_container_extra_classes('vp-checked-field');
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
		return VP_View::instance()->load('control/checkbox', $this->get_data());
	}

}

/**
 * EOF
 */