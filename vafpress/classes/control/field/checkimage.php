<?php

class VP_Control_Field_CheckImage extends VP_Control_FieldMultiImage
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
		return VP_View::get_instance()->load('control/checkimage', $this->get_data());
	}

}

/**
 * EOF
 */