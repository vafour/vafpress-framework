<?php

class VP_Option_Control_Group_Menu extends VP_Option_Control_Group
{

	/**
	 * Collection of $_menu
	 * @var VP_Option_Control_Group
	 */	
	private $_menus;

	/**
	 * Collection of sections
	 * @var VP_Control_Field
	 */	
	private $_sections;

	private $_icon;

	public function __construct()
	{
		$this->_menus    = array();
		$this->_sections = array();
	}

	public function render($extra = array())
	{
		// Setup data
		$this->_setup_data();
		$this->add_data('menu', $this);
		foreach ($extra as $key => $value)
		{
			$this->add_data($key, $value);
		}
		return VP_View::get_instance()->load('option/menu', $this->get_data());
	}

	public function add_menu($menu)
	{
		$this->_menus[] = $menu;
	}

	/**
	 * Getter of $_menus
	 *
	 * @return Array Collection of menus object
	 */
	public function get_menus() {
		return $this->_menus;
	}
	
	/**
	 * Setter of $_menus
	 *
	 * @param Array $_menus Collection of menus object
	 */
	public function set_menus($_menus)
	{
		$this->_menus = $_menus;
		return $this;
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
	public function get_sections()
	{
		return $this->_sections;
	}
	
	/**
	 * Setter of sections
	 *
	 * @param Array $_sections Collection of sections object
	 */
	public function set_sections($_sections)
	{
		$this->_sections = $_sections;
		return $this;
	}

	/**
	 * Get menu icon
	 *
	 * @return String Icon URL
	 */
	public function get_icon() {
	    return $this->_icon;
	}
	
	/**
	 * Set menu icon
	 *
	 * @param String $_icon Icon URL
	 */
	public function set_icon($_icon) {
	    $this->_icon = $_icon;
	    return $this;
	}

}