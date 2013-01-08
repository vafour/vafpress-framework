<?php

/**
 * The smallest unit of an item, the field it self.
 */
abstract class VP_Control_FieldMultiImage extends VP_Control_FieldMulti
{

	protected $item_max_height;

	protected $item_max_width;

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

		if (!empty($arr['items']))
		{
			$assoc = array();
			foreach ($arr['items'] as $item)
			{
				$assoc[$item['value']] = $item['img'];
			}
			$items = $this->get_items();
			foreach ($items as &$item)
			{
				$item->img($assoc[$item->value]);
			}
		}
		return $this;
	}

	protected function _setup_data()
	{
		parent::_setup_data();
		$this->add_data('item_max_height', $this->get_item_max_height());
		$this->add_data('item_max_width', $this->get_item_max_width());
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