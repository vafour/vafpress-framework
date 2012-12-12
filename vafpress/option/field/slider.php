<?php

class VP_Option_Field_Slider extends VP_Option_Field
{

	private $_min;

	private $_max;

	private $_step;

	public function __construct()
	{
		
	}

	public static function withArray($arr)
	{
		$instance = new self();
		$instance->set_min($arr['min']);
		$instance->set_max($arr['max']);
		$instance->set_step($arr['step']);
		$instance->_basic_make($arr);
		return $instance;
	}

	public function render()
	{
		// Setup Data
		$this->_setup_data();
		$opt = array(
			'min'   => $this->get_min(),
			'max'   => $this->get_max(),
			'step' => $this->get_step(),
			'value' => $this->get_value(),
		);
		$this->add_data('opt', VP_Util_Text::make_opt($opt));
		$this->add_data('opt_raw', $opt);
		return VP_Option_View::get_instance()->load('slider', $this->get_data());
	}

	protected function _basic_make($simpleXML)
	{
		parent::_basic_make($simpleXML);
		$default = $this->get_default();
		if (empty($default))
		{
			$this->set_default($this->get_min());
		}
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