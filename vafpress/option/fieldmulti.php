<?php

/**
 * The smallest unit of an item, the field it self.
 */
abstract class VP_Option_FieldMulti extends VP_Option_Field
{

	protected $_items = array();

	/**
	 * Basic self setup of the object
	 * @param  SimpleXMLElement $simpleXML SimpleXML object representation of the field
	 * @return VP_Option_FieldMulti Field object
	 */
	protected function _basic_make($arr)
	{
		parent::_basic_make($arr);

		if (!empty($arr['items']))
		{
			if(isset($arr['items']['data']))
			{
				foreach ($arr['items']['data'] as $data)
				{
					if($data['type'] == 'function')
					{
						$first    = strpos($data['name'], '(');
						$last     = strpos($data['name'], ')');
						if( $first && $last )
						{
							$function = substr($data['name'], 0, $first);
							$params   = explode(',', substr($data['name'], $first + 1, $last - $first - 1));					
						}
						else
						{
							$function = $data['name'];
							$params   = array();
						}
						if(function_exists($function))
						{
							$items = call_user_func_array($function, $params);
							$arr['items'] = array_merge($arr['items'], $items);
						}
					}
				}
				unset($arr['items']['data']);
			}
			foreach ($arr['items'] as $item)
			{
				// if item has image, skip
				if (isset($item['img'])) { break; }

				$the_item = new VP_Option_Field_Item_Plain();
				$the_item->value($item['value'])
					 	 ->label($item['label']);
				$this->add_item($the_item);
			}
		}
		if (!empty($arr['default']))
		{
			$this->_process_default($arr['default'], $arr['items']);
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
		$this->add_data('items', $this->get_items());
	}

	public function add_items($items)
	{
		$this->_items = array_merge($this->_items, $items);
	}

	/**
	 * Add single item
	 * @param VP_Option_Field_Item_ $opt Single item item
	 */
	public function add_item($opt)
	{
		$this->_items[] = $opt;
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

}

/**
 * EOF
 */