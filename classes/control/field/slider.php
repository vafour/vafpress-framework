<?php

class VP_Control_Field_Slider extends VP_Control_Field
{

	private $_min;

	private $_max;

	private $_step;

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
		$instance->set_min(isset($arr['min']) ? $arr['min'] : 0);
		$instance->set_max(isset($arr['max']) ? $arr['max'] : 100);
		$instance->set_step(isset($arr['step']) ? $arr['step'] : 1);
		$instance->_basic_make($arr);
		return $instance;
	}

	protected function _setup_data()
	{
		$opt = array(
			'min'   => $this->get_min(),
			'max'   => $this->get_max(),
			'step'  => $this->get_step(),
			'value' => $this->get_value(),
		);
		$this->add_data('opt', VP_Util_Text::make_opt($opt));
		$this->add_data('opt_raw', $opt);
		parent::_setup_data();
	}

	public function render($is_compact = false)
	{
		// Setup Data
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/slider', $this->get_data());
	}

	protected function _basic_make($arr)
	{
		parent::_basic_make($arr);
		$default = $this->get_default();
		$default = $this->validate_value($default);
		$this->set_default($default);
	}

	protected function validate_value($_value)
	{
		$out_range = (floatval($_value) < $this->get_min()) || (floatval($_value) > $this->get_max());

		if (is_null($_value) || $out_range)
			return $this->get_min();
		else
			return $_value;
	}

	public function set_value($_value)
	{
		$_value = $this->validate_value($_value);
		parent::set_value($_value);
	}

	/**
	 * Get the min value
	 *
	 * @return Integer Minimum value of slider
	 */
	public function get_min() {
		return $this->_min;
	}
	
	/**
	 * Set the min value
	 *
	 * @param Integer $_min Minimum value of slider
	 */
	public function set_min($_min) {
		$this->_min = $_min;
		return $this;
	}

	/**
	 * Get the max value
	 *
	 * @return Integer Maximum value of slider
	 */
	public function get_max() {
		return $this->_max;
	}
	
	/**
	 * Set the max value
	 *
	 * @param Integer $_max Maximum value of slider
	 */
	public function set_max($_max) {
		$this->_max = $_max;
		return $this;
	}

	/**
	 * Get the step value
	 *
	 * @return Integer Step value of slider
	 */
	public function get_step() {
		return $this->_step;
	}
	
	/**
	 * Set the step value
	 *
	 * @param Integer $_step Step value of slider
	 */
	public function set_step($_step) {
		$this->_step = $_step;
		return $this;
	}

}

/**
 * EOF
 */