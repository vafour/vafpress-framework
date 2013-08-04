<?php

/**
 * The smallest unit of an item, the field it self.
 */
abstract class VP_Control_FieldMultiImage extends VP_Control_FieldMulti
{

	protected $_item_max_height;

	protected $_item_max_width;

	/**
	 * Basic self setup of the object
	 * @param  SimpleXMLElement $simpleXML SimpleXML object representation of the field
	 * @return VP_Control_FieldMultiImage Field object
	 */
	protected function _basic_make($arr)
	{
		parent::_basic_make($arr);
		
		$this->set_item_max_height(isset($arr['item_max_height']) ? $arr['item_max_height'] : '')
		     ->set_item_max_width(isset($arr['item_max_width']) ? $arr['item_max_width'] : '');

		return $this;
	}

	protected function _setup_data()
	{
		parent::_setup_data();
		$this->add_data('item_max_height', $this->get_item_max_height());
		$this->add_data('item_max_width', $this->get_item_max_width());
	}

	public function add_items_from_array($_items){
		foreach ($_items as $item)
		{
			$the_item = new VP_Control_Field_Item_Generic();
			$the_item->value($item['value'])
				 	 ->label($item['label'])
				 	 ->img($item['img']);
			$this->add_item($the_item);
		}
	}

	/**
	 * Get item max height
	 *
	 * @return Integer Item Max Height
	 */
	public function get_item_max_height() {
		return $this->_item_max_height;
	}
	
	/**
	 * Set item max height
	 *
	 * @param Integer $_item_max_height Item Max Height
	 */
	public function set_item_max_height($_item_max_height) {
		$this->_item_max_height = $_item_max_height;
		return $this;
	}

	/**
	 * Get item max width
	 *
	 * @return Integer Item Max Width
	 */
	public function get_item_max_width() {
		return $this->_item_max_width;
	}
	
	/**
	 * Set item max width
	 *
	 * @param Integer $_item_max_width Item Max Width
	 */
	public function set_item_max_width($_item_max_width) {
		$this->_item_max_width = $_item_max_width;
		return $this;
	}

}

/**
 * EOF
 */