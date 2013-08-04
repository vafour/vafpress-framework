<?php

/**
 * The smallest unit of an item, the field it self.
 */
abstract class VP_Control_FieldMulti extends VP_Control_Field
{

	protected $_items = array();

	protected $_items_binding;

	/**
	 * Basic self setup of the object
	 * @param  SimpleXMLElement $simpleXML SimpleXML object representation of the field
	 * @return VP_Control_FieldMulti Field object
	 */
	protected function _basic_make($arr)
	{
		parent::_basic_make($arr);

		if (!empty($arr['items']))
		{
			if(isset($arr['items']['data']) and is_array($arr['items']['data']))
			{
				foreach ($arr['items']['data'] as $data)
				{
					if($data['source'] == 'function')
					{
						$function = $data['value'];
						$params   = explode(',', !empty($data['params']) ? $data['params'] : '');

						if(function_exists($function))
						{
							$items = call_user_func_array($function, $params);
							$arr['items'] = array_merge($arr['items'], $items);
						}
					}
					else if($data['source'] == 'binding')
					{
						$function = $data['value'];
						$field    = $data['field'];
						$this->set_items_binding($function . '|' . $field);
					}
				}
				unset($arr['items']['data']);
			}
			if(is_array($arr['items'])) foreach ($arr['items'] as $item)
			{
				$the_item = new VP_Control_Field_Item_Generic();
				$the_item->value($item['value'])
					 	 ->label($item['label']);
				if( isset( $item['img'] ) )
					$the_item->img($item['img']);
				$this->add_item($the_item);
			}
		}
		if (isset($arr['default']))
		{
			$arr['default'] = (array) $arr['default'];
			if(!VP_Util_Reflection::is_multiselectable($this))
			{
				$arr['default'] = (array) reset($arr['default']);
			}
			if(is_array($arr['default']))
			{
				$this->_process_default($arr['default'], $arr['items']);
			}
		}
		return $this;
	}

	protected function _process_default($arr_default, $arr_items)
	{
		$defaults = array();
		foreach ($arr_default as $def)
		{
			switch ($def)
			{
				case '{{all}}':
					if(VP_Util_Reflection::is_multiselectable($this))
						$defaults = array_merge($defaults, VP_Util_Array::deep_values($arr_items, 'value'));
					break;
				case '{{first}}':
					$first = VP_Util_Array::first($arr_items);
					$defaults[] = $first['value'];
					break;
				case '{{last}}':
					$last = end($arr_items);
					$defaults[] = $last['value'];
					break;
				default:
					if(array_key_exists($def, $this->get_items()))
						$defaults[] = $def;
					break;
			}
		}
		$defaults = array_unique($defaults);
		$this->set_default($defaults);
	}

	protected function _setup_data()
	{
		parent::_setup_data();
		$this->add_single_data('head_info', 'items_binding', $this->get_items_binding());
		$this->add_data('items', $this->get_items());
	}

	public function add_items($items)
	{
		$this->_items = array_merge($this->_items, $items);
	}

	/**
	 * Add single item
	 * @param VP_Control_Field_Item_ $opt Single item item
	 */
	public function add_item($opt)
	{
		$this->_items[$opt->value] = $opt;
	}

	/**
	 * Getter for $_items
	 *
	 * @return Array array of items {value, label}
	 */
	public function get_items() {
		return $this->_items;
	}
	
	/**
	 * Setter for $_items
	 *
	 * @param Array $_items array of items
	 */
	public function set_items($_items) {
		$this->_items = $_items;
		return $this;
	}

	public function add_items_from_array($_items) {
		if(is_array($_items))
		{
			foreach ($_items as $item)
			{
				$the_item = new VP_Control_Field_Item_Generic();
				$the_item->value($item['value'])
					 	 ->label($item['label']);
				$this->add_item($the_item);
			}
		}
	}

	/**
	 * Get $_items_binding
	 *
	 * @return String bind rule string
	 */
	public function get_items_binding() {
		return $this->_items_binding;
	}
	
	/**
	 * Set $_items_binding
	 *
	 * @param String $_items_binding bind rule string
	 */
	public function set_items_binding($_items_binding) {
		$this->_items_binding = $_items_binding;
		return $this;
	}

}

/**
 * EOF
 */