<?php

class VP_Option_Field_TextArea extends VP_Option_Field
{

	public function __construct()
	{
		
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
		return VP_Option_View::get_instance()->load('textarea', $this->get_data());
	}

}

/**
 * EOF
 */