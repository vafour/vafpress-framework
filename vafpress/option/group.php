<?php

abstract class VP_Option_Group
{


	protected $_name;

	protected $_title;

	protected $_description;

	protected $_data;

	public function __construct()
	{

	}

	public abstract function render($extra = array());

	protected function _setup_data(){}

	/**
	 * Getter of $_name
	 *
	 * @return String Group unique name
	 */
	public function get_name() {
		return $this->_name;
	}
	
	/**
	 * Setter of $_name
	 *
	 * @param String $_name Group unique name
	 */
	public function set_name($_name) {
		$this->_name = $_name;
		return $this;
	}

	/**
	 * Getter of title
	 *
	 * @return String Group title
	 */
	public function get_title() {
		return $this->_title;
	}
	
	/**
	 * Setter of title
	 *
	 * @param String $_title Group title
	 */
	public function set_title($_title) {
		$this->_title = $_title;
		return $this;
	}


	/**
	 * Getter of $_description
	 *
	 * @return String Group description
	 */
	public function get_description() {
		return $this->_description;
	}
	
	/**
	 * Setter of $_description
	 *
	 * @param String $_description Group description
	 */
	public function set_description($_description) {
		$this->_description = $_description;
		return $this;
	}

	/**
	 * Add value to render data array
	 * @param Mixed $item Value to be added to render data arary
	 */
	public function add_data($key, $value)
	{
		$this->_data[$key] = $value;
	}

	/**
	 * Get render data
	 *
	 * @return Array Render data array
	 */
	public function get_data() {
	    return $this->_data;
	}
	
	/**
	 * Set render data
	 *
	 * @param Array $_data Render data array
	 */
	public function set_data($_data) {
	    $this->_data = $_data;
	    return $this;
	}

}