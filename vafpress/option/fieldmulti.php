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
						if(function_exists($data['name']))
						{
							$items = call_user_func($data['name']);
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
			$this->set_default($arr['default']);
		}
		return $this;
	}

	protected function _setup_data()
	{
		parent::_setup_data();
		$this->add_data('items', $this->get_items());
	}

	public function add_items($items)
	{
		$this->_items = array_merge($this->_items, $items);
		// $this->_items = $this->_items + $items;
		// print_r($this->_items);
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