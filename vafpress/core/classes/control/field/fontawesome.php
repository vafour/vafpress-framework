<?php

class VP_Control_Field_Fontawesome extends VP_Control_FieldMulti
{

	public function __construct()
	{
		parent::__construct();
		$this->_value = array();
	}

	public static function withArray($arr = array())
	{
		$instance = new self();
		$arr['items']['data'][] = array(
			'source' => 'function',
			'value' => 'vp_get_fontawesome_icons',
		);

		$instance->_basic_make($arr);

		// Turn default array to single value
		$instance->set_default(VP_Util_Array::first($instance->get_default()));
		
		return $instance;
	}

	public function render($is_compact = false)
	{
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/fontawesome', $this->get_data());
	}

}

/**
 * EOF
 */