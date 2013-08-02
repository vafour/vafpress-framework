<?php

class VP_Control_Field_Sorter extends VP_Control_FieldMulti implements VP_MultiSelectable
{

	private $_max_selection;

	public function __construct()
	{
		parent::__construct();
		$this->_value = array();
	}

	public static function withArray($arr = array(), $class_name = null)
	{
		if(is_null($class_name))
			$instance = new self();
		else
			$instance = new $class_name;
		$instance->set_max_selection(isset($arr['max_selection']) ? $arr['max_selection'] : false);
		$instance->_basic_make($arr);
		
		return $instance;
	}

	protected function _setup_data()
	{
		$opt = array(
			'maximumSelectionSize' => $this->get_max_selection(),
		);
		$this->add_data('opt', VP_Util_Text::make_opt($opt));
		$this->add_data('opt_raw', $opt);
		parent::_setup_data();
	}

	public function render($is_compact = false)
	{
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/sorter', $this->get_data());
	}

	public function get_max_selection() {
		return $this->_max_selection;
	}
	
	public function set_max_selection($_max_selection) {
		$this->_max_selection = $_max_selection;
		return $this;
	}

}

/**
 * EOF
 */