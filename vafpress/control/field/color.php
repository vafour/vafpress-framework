<?php

class VP_Control_Field_Color extends VP_Control_Field
{

	public function __construct()
	{
		parent::__construct();
	}

	public static function withArray($arr)
	{
		$instance = new self();
		$instance->_basic_make($arr);
		return $instance;
	}

	protected function _basic_make($arr)
	{
		parent::_basic_make($arr);
		$default = $this->get_default();
		if (empty($default))
		{
			$this->set_default('#000000');
		}
	}

	public function render()
	{
		$this->_setup_data();
		return VP_View::get_instance()->load('control/color', $this->get_data());
	}
}

/**
 * EOF
 */