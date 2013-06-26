<?php

class VP_Option_Control_Group_Section extends VP_Option_Control_Group
{

	/**
	 * Collection of fields
	 * @var VP_Control_Field
	 */	
	private $_fields;

	/**
	 * dependency pattern string
	 * @var String
	 */
	protected $_dependency;

	/**
	 * Whether to hide this control in first rendering
	 */
	protected $_is_hidden;

	public function __construct()
	{
		parent::__construct();
		$this->_fields = array();
	}

	public function render($extra = array())
	{
		// Setup data
		$this->_setup_data();

		if($this->is_hidden())
		{
			$this->add_container_extra_classes('vp-hide');
		}

		$this->add_data('section', $this);
		$this->add_data('container_extra_classes', implode(' ', $this->get_container_extra_classes()));

		foreach ($extra as $key => $value)
		{
			$this->add_data($key, $value);
		}
		return VP_View::instance()->load('option/section', $this->get_data());
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
	 * Getter for $_dependency
	 *
	 * @return String dependency pattern in string
	 */
	public function get_dependency() {
		return $this->_dependency;
	}
	
	/**
	 * Setter for $_dependency
	 *
	 * @param String $_dependency dependency pattern in string
	 */
	public function set_dependency($_dependency) {
		$this->_dependency = $_dependency;
		return $this;
	}

	/**
	 * Get is_hidden status, will set the status if a boolean passed
	 *
	 * @return bool is_hidden status
	 */
	public function is_hidden($_is_hidden = null) {
		if(!is_null($_is_hidden))
	    	$this->_is_hidden = (bool) $_is_hidden;
		return $this->_is_hidden;
	}

}