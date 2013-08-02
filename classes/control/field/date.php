<?php

class VP_Control_Field_Date extends VP_Control_Field
{

	private $_min_date;

	private $_max_date;

	private $_format;

	public function __construct()
	{
		parent::__construct();
	}

	public static function withArray($arr = array(), $class_name = null)
	{
		if(is_null($class_name))
			$instance = new self();
		else
			$instance = new $class_name;
		$instance->_basic_make($arr);
		$instance->set_min_date(isset($arr['min_date']) ? $arr['min_date'] : '');
		$instance->set_max_date(isset($arr['max_date']) ? $arr['max_date'] : '');
		$instance->set_format(isset($arr['format']) ? $arr['format'] : 'yy-mm-dd');
		return $instance;
	}

	protected function _setup_data()
	{
		$opt = array(
			'minDate'    => $this->get_min_date(),
			'maxDate'    => $this->get_max_date(),
			'dateFormat' => $this->get_format(),
			'value'      => $this->get_value()
		);
		$this->add_data('opt', VP_Util_Text::make_opt($opt));
		parent::_setup_data();
	}

	public function render($is_compact = false)
	{
		// Setup Data
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/date', $this->get_data());
	}

	/**
	 * Get Minimum Date
	 *
	 * @return String Minimum Date
	 */
	public function get_min_date() {
		return $this->_min_date;
	}
	
	/**
	 * Set Minimum Date
	 *
	 * @param String $_min_date Minimum Date
	 */
	public function set_min_date($_min_date) {
		$this->_min_date = $_min_date;
		return $this;
	}

	/**
	 * Get Maximum Date
	 *
	 * @return String Maximum Date
	 */
	public function get_max_date() {
		return $this->_max_date;
	}
	
	/**
	 * Set Maximum Date
	 *
	 * @param String $_max_date Maximum Date
	 */
	public function set_max_date($_max_date) {
		$this->_max_date = $_max_date;
		return $this;
	}


	/**
	 * Get Date Format
	 *
	 * @return String Date format
	 */
	public function get_format() {
		return $this->_format;
	}
	
	/**
	 * Set Date Format
	 *
	 * @param String $_format Date format
	 */
	public function set_format($_format) {
		$this->_format = $_format;
		return $this;
	}

}

/**
 * EOF
 */