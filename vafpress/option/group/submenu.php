<?php

class VP_Option_Group_Submenu extends VP_Option_Group
{

	/**
	 * Collection of sections
	 * @var VP_Option_Field
	 */	
	private $_sections;

	public function __construct()
	{
		$this->_sections = array();
	}

	public function render($extra = array())
	{
		// Setup data
		$this->_setup_data();
		$this->add_data('submenu', $this);
		foreach ($extra as $key => $value)
		{
			$this->add_data($key, $value);
		}
		return VP_Option_View::get_instance()->load('submenu', $this->get_data());
	}

	public function add_section($section)
	{
		$this->_sections[] = $section;
	}

	/**
	 * Getter of sections
	 *
	 * @return Array Collection of sections object
	 */
	public function get_sections() {
		return $this->_sections;
	}
	
	/**
	 * Setter of sections
	 *
	 * @param Array $_sections Collection of sections object
	 */
	public function set_sections($_sections) {
		$this->_sections = $_sections;
		return $this;
	}

}