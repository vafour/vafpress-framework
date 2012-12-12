<?php

class VP_Option_Set
{

	private $_menus;

	private $_title;

	private $_page;

	private $_logo;

	public function __construct()
	{

	}

	public function render()
	{
		// Setup data
		$data = array('set' => $this);
		return VP_Option_View::get_instance()->load('set', $data);
	}

	/**
	 * Get Option Set Title
	 *
	 * @return String Option set title
	 */
	public function get_title() {
	    return $this->_title;
	}
	
	/**
	 * Set Option Set title
	 *
	 * @param String $_title Option set title
	 */
	public function set_title($_title) {
	    $this->_title = $_title;
	    return $this;
	}

	/**
	 * Get page name
	 *
	 * @return String Page name
	 */
	public function get_page() {
	    return $this->_page;
	}
	
	/**
	 * Set page name
	 *
	 * @param String $_page Page name
	 */
	public function set_page($_page) {
	    $this->_page = $_page;
	    return $this;
	}

	/**
	 * Get logo
	 *
	 * @return String Logo URL
	 */
	public function get_logo() {
	    return $this->_logo;
	}
	
	/**
	 * Set logo
	 *
	 * @param String $_logo Logo URL
	 */
	public function set_logo($_logo) {
	    $this->_logo = $_logo;
	    return $this;
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
	public function set_menus($_menus) {
		$this->_menus = $_menus;
		return $this;
	}

	public function get_fields()
	{
		$fields = array();
		foreach ( $this->_menus as $menu )
		{
			$submenus = $menu->get_menus();
			if( !empty($submenus) )
			{
				foreach ( $submenus as $submenu )
				{
					foreach ( $submenu->get_sections() as $section )
					{
						foreach ( $section->get_fields() as $field )
						{
							if( VP_Util_Text::field_type_from_class(get_class($field)) != 'impexp' )
								$fields[$field->get_name()] = $field;
						}
					}
				}
			}
			else
			{
				foreach ( $menu->get_sections() as $section )
				{
					foreach ( $section->get_fields() as $field )
					{
						if( VP_Util_Text::field_type_from_class(get_class($field)) != 'impexp' )
							$fields[$field->get_name()] = $field;
					}
				}
			}
		}
		return $fields;
	}

	public function normalize_values($opt_arr)
	{
		$fields        = $this->get_fields();
		$multi_classes = array( 'checkbox', 'checkimage', 'multiselect' );

		foreach ($opt_arr as $key => $value)
		{
			if(array_key_exists($key, $fields))
			{
				$type     = VP_Util_text::field_type_from_class(get_class($fields[$key]));
				$is_multi = in_array($type, $multi_classes);
				if( $is_multi and !is_array($value) )
				{
					$opt_arr[$key] = array($value);
				}
				if( !$is_multi and  is_array($value))
				{
					$opt_arr[$key] = '';
				}
			}
		}
		return $opt_arr;
	}

	public function get_defaults()
	{
		$defaults = array();
		$fields   = $this->get_fields();
		foreach ( $fields as $field )
		{
			$defaults[$field->get_name()] = $field->get_default();
		}
		return $defaults;
	}

	public function get_values()
	{
		$values = array();
		$fields = $this->get_fields();
		foreach ( $fields as $field )
		{
			$values[$field->get_name()] = $field->get_value();
		}
		return $values;
	}

	public function save($option_key)
	{
		$opt = $this->get_values();
		if(update_option($option_key, $opt))
		{
			$result['status']  = true;
			$result['message'] = "Saving success.";
		}
		else
		{
			$curr_opt = get_option($option_key, array());
			$changed  = $opt !== $curr_opt;
			if($changed)
			{
				$result['status']  = false;
				$result['message'] = "Saving failed.";
			}
			else
			{
				$result['status']  = true;
				$result['message'] = "No changes made.";
			}
		}
		return $result;
	}

	public function populate_values($opt)
	{
		$fields        = $this->get_fields();
		$multi_classes = array( 'checkbox', 'checkimage', 'multiselect' );
		foreach ( $fields as $field )
		{
			$type     = VP_Util_text::field_type_from_class(get_class($field));
			$is_multi = in_array($type, $multi_classes);
			
			if( array_key_exists($field->get_name(), $opt) )
			{
				if( $is_multi and is_array($opt[$field->get_name()]) )
				{
					$field->set_value($opt[$field->get_name()]);
				}
				if( !$is_multi and !is_array($opt[$field->get_name()]) )
				{
					$field->set_value($opt[$field->get_name()]);
				}
			}
		}
	}

}

/**
 * EOF
 */