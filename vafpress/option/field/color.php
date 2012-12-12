<?php

class VP_Option_Field_Color extends VP_Option_Field
{

	public function __construct(){}

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
		return VP_Option_View::get_instance()->load('color', $this->get_data());
	}
}

/**
 * EOF
 */