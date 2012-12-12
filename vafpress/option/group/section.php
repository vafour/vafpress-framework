<?php

class VP_Option_Group_Section extends VP_Option_Group
{

	/**
	 * Collection of fields
	 * @var VP_Option_Field
	 */	
	private $_fields;

	public function __construct()
	{
		$this->_fields = array();
	}

	public function render($extra = array())
	{
		// Setup data
		$this->_setup_data();
		$this->add_data('section', $this);
		foreach ($extra as $key => $value)
		{
			$this->add_data($key, $value);
		}
		return VP_Option_View::get_instance()->load('section', $this->get_data());
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

}