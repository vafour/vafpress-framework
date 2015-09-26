<?php

class VP_Option_Control_Set
{

	const SAVE_SUCCESS   = 1;

	const SAVE_NOCHANGES = 2;

	const SAVE_FAILED    = 3;

	private $_menus;

	private $_title;

	private $_logo;

	private $_layout;

	public function __construct()
	{
		$this->_menus = array();
	}

	public function render()
	{
		// Setup data
		$data = array('set' => $this);
		return VP_View::instance()->load('option/set', $data);
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
	 * Set _layout
	 *
	 * @return String _layout
	 */
	public function get_layout()
	{
		return $this->_layout;
	}
	
	/**
	 * Get _layout
	 *
	 * @param String $_layout _layout
	 */
	public function set_layout($_layout)
	{
		$this->_layout = $_layout;
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

	public function get_fields($include_section = false)
	{
		if(!function_exists('loop_controls'))
		{
			function loop_controls($menu, $include_section)
			{
				$fields = array();
				foreach ( $menu->get_controls() as $control )
				{
					if( get_class($control) === 'VP_Option_Control_Group_Section' )
					{
						if($include_section)
						{
							$fields[$control->get_name()] = $control;
						}
						foreach ( $control->get_fields() as $field )
						{
							if( VP_Util_Reflection::field_type_from_class(get_class($field)) != 'impexp' )
							{
								$fields[$field->get_name()] = $field;
							}
						}
					}
					else
					{
						if( VP_Util_Reflection::field_type_from_class(get_class($control)) != 'impexp' )
						{
							$fields[$control->get_name()] = $control;
						}
					}
				}
				return $fields;
			}
		}

		$fields = array();

		foreach ( $this->_menus as $menu )
		{
			$submenus = $menu->get_menus();
			if( !empty($submenus) )
			{
				foreach ( $submenus as $submenu )
				{
					$fields = array_merge($fields, loop_controls($submenu, $include_section));
				}
			}
			else
			{
				$fields = array_merge($fields, loop_controls($menu, $include_section));
			}
		}
		return $fields;
	}

	public function get_field_types()
	{
		$fields = $this->get_fields();
		$types  = array();
		foreach ($fields as $field)
		{
			$type = VP_Util_Reflection::field_type_from_class(get_class($field));
			if(!in_array($type, $types))
				$types[] = $type;
		}
		return $types;
	}

	public function get_field($name)
	{
		$fields = $this->get_fields();
		if(array_key_exists($name, $fields))
		{
			return $fields[$name];
		}
		return null;
	}

	public function process_binding()
	{
		
		$fields = $this->get_fields();

		foreach ($fields as $field)
		{
			$bind = $field->get_binding();
			$val  = $field->get_value();
			if(!empty($bind) and is_null($val))
			{
				$bind   = explode('|', $bind);
				$func   = $bind[0];
				$params = $bind[1];
				$params = preg_split('/[\s,]+/', $params);
				$values = array();
				foreach ($params as $param)
				{
					if(array_key_exists($param, $fields))
					{
						$values[] = $fields[$param]->get_value();
					}
				}
				$result = call_user_func_array($func, $values);

				if(VP_Util_Reflection::is_multiselectable($field))
				{
					$result = (array) $result;
				}
				else
				{
					if(is_array($result))
					{
						$result = reset($result);
					}
					$result = (String) $result;
				}
				$field->set_value($result);
			}

			if($field instanceof VP_Control_FieldMulti)
			{
				$bind = $field->get_items_binding();
				if(!empty($bind))
				{
					$bind   = explode('|', $bind);
					$func   = $bind[0];
					$params = $bind[1];
					$params = preg_split('/[\s,]+/', $params);
					$values = array();
					foreach ($params as $param)
					{
						if(array_key_exists($param, $fields))
						{
							$values[] = $fields[$param]->get_value();
						}
					}
					$items  = call_user_func_array($func, $values);
					if(is_array($items) && !empty($items))
					{
						$field->set_items(array());
						$field->add_items_from_array($items);
					}
				}
			}
		}
	}

	public function process_dependencies()
	{
		$fields = $this->get_fields(true);

		foreach ($fields as $field)
		{
			$dependency = $field->get_dependency();
			if(!empty($dependency))
			{
				$dependency = explode('|', $dependency);
				$func       = $dependency[0];
				$params     = $dependency[1];
				$params     = preg_split('/[\s,]+/', $params);
				$values     = array();
				foreach ($params as $param)
				{
					if(array_key_exists($param, $fields))
					{
						$values[] = $fields[$param]->get_value();
					}
				}
				$result  = call_user_func_array($func, $values);
				if(!$result)
				{
					$field->add_container_extra_classes('vp-dep-inactive');
					$field->is_hidden(true);
				}
			}
		}
	}


	public function normalize_values($opt_arr)
	{
		$fields = $this->get_fields();

		foreach ($opt_arr as $key => $value)
		{
			if(array_key_exists($key, $fields))
			{
				$is_multi = VP_Util_Reflection::is_multiselectable($fields[$key]);
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
			// Use 'dot' separate field name to array
			if (strpos($field->get_name(), '.') !== false) {
				$arrField     = explode('.', $field->get_name());
				$fieldNameEnd = &$values;

				foreach ($arrField as $i => $fieldName) {
					$fieldNameEnd = &$fieldNameEnd[$fieldName];
				}

				$fieldNameEnd = $field->get_value();
			} else
			// End 
			{
				$values[$field->get_name()] = $field->get_value();
			}

		}
		return $values;
	}

	public function setup($options)
	{
		// populate option to fields' values
		$this->populate_values($options, false, false);

		// process binding
		$this->process_binding();

		// process dependencies
		$this->process_dependencies();
	}

	public function save($option_key)
	{
		$opt = $this->get_values();

		do_action('vp_option_set_before_save', $opt);

		if(update_option($option_key, $opt))
		{
			$result['status']  = true;
			$result['code']    = self::SAVE_SUCCESS;
			$result['message'] = __('Saving successful', 'vp_textdomain');
			$curr_opt = get_option($option_key, array());
		}
		else
		{
			$curr_opt = get_option($option_key, array());
			$changed  = $opt !== $curr_opt;
			if($changed)
			{
				$result['status']  = false;
				$result['code']    = self::SAVE_FAILED;
				$result['message'] = __('Saving failed', 'vp_textdomain');
			}
			else
			{
				$result['status']  = true;
				$result['code']    = self::SAVE_NOCHANGES;
				$result['message'] = __('No changes made', 'vp_textdomain');
			}
		}

		do_action('vp_option_set_after_save', $curr_opt, $result['status'], $option_key);

		return $result;
	}

	// add $save_populate - it check if populate when save or first load panel
	public function populate_values($opt, $force_update = false, $save_populate = true) {
		$fields = $this->get_fields();
		foreach ($fields as $field) {
			$is_multi = VP_Util_Reflection::is_multiselectable($field);

			if (strpos($field->get_name(), '.') !== false && !$save_populate) {
				$arrField = explode('.', $field->get_name());
				$fieldEnd = $opt;

				foreach ($arrField as $i => $fieldName) {
					$fieldEnd = $fieldEnd[$fieldName];
				}

				if (!is_null($fieldEnd)) {
					if ($is_multi and is_array($fieldEnd)) {
						$field->set_value($fieldEnd);
					}
					if (!$is_multi and !is_array($fieldEnd)) {
						$field->set_value($fieldEnd);
					}
				}
			}
			else {

				if (array_key_exists($field->get_name(), $opt)) {
					if ($is_multi and is_array($opt[$field->get_name()])) {
						$field->set_value($opt[$field->get_name()]);
					}
					if (!$is_multi and !is_array($opt[$field->get_name()])) {
						$field->set_value($opt[$field->get_name()]);
					}
				} else {
					if ($force_update) {
						if ($is_multi) {
							$field->set_value(array());
						} else {
							$field->set_value('');
						}
					}
				}
			}
		}
	}

}

/**
 * EOF
 */
