<?php

class VP_Option_Control_Group_Section extends VP_Option_Control_Group
{

	/**
	 * Collection of fields
	 * @var VP_Control_Field
	 */	
	private $_fields;

	/**
	 * Dependancy pattern string
	 * @var String
	 */
	protected $_dependancy;

	public function __construct()
	{
		parent::__construct();
		$this->_fields = array();
	}

	public function render($extra = array())
	{
		// Setup data
		$this->_setup_data();
		$this->add_data('section', $this);
		$this->add_data('container_extra_classes', implode(',', $this->get_container_extra_classes()));
		foreach ($extra as $key => $value)
		{
			$this->add_data($key, $value);
		}
		return VP_View::get_instance()->load('option/section', $this->get_data());
	}

	public function add_field($field)
	{
		$this->_fields[] = $field;
	}

	/**
	 * Getter of fields
	 *
	 * @return Array Collection of fields object
	 */
	public function get_fields() {
		return $this->_fields;
	}
	
	/**
	 * Setter of fields
	 *
	 * @param Array $_fields Collection of fields object
	 */
	public function set_fields($_fields) {
		$this->_fields = $_fields;
		return $this;
	}

	/**
	 * Getter for $_dependancy
	 *
	 * @return String dependancy pattern in string
	 */
	public function get_dependancy() {
		return $this->_dependancy;
	}
	
	/**
	 * Setter for $_dependancy
	 *
	 * @param String $_dependancy dependancy pattern in string
	 */
	public function set_dependancy($_dependancy) {
		$this->_dependancy = $_dependancy;
		return $this;
	}

}