<?php

class VP_Control_Field_HTML extends VP_Control_Field
{

	protected $_height;

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
		$instance->set_height(isset($arr['height']) ? $arr['height'] : 'auto');
		return $instance;
	}

	protected function _setup_data()
	{
		$this->add_data('height', $this->get_height());
		parent::_setup_data();
	}

	public function render($is_compact = false)
	{
		// Setup Data
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/html', $this->get_data());
	}

	public function set_value($_value)
	{
		// normalize linebreak to \n for all saved data
		if( is_string($_value) )
		{
			$_value = str_replace(array("\r\n", "\r"), "\n", $_value);
		}
		$this->_value = $_value;
		return $this;
	}


	/**
	 * Get the Height of the Container
	 *
	 * @return String Height of the Container
	 */
	public function get_height() {
		return $this->_height;
	}
	
	/**
	 * Set the Height of the Container
	 *
	 * @param String $_status Height of the Container
	 */
	public function set_height($_height) {
		$this->_height = $_height;
		return $this;
	}

}

/**
 * EOF
 */